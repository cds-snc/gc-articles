const { SelectControl } = wp.components;
const { useState, useEffect } = wp.element;
const { select, subscribe } = wp.data;
const { __ } = wp.i18n;

import { getData, sendData } from '../util/fetch.js';

/**
 * The WPML metabox panel is removed using "removeEditorPanel" in sidebar.js, but that
 * just hides the metabox using CSS, it's not actually removed from the DOM.
 *
 * The WPML metabox loads with a pre-populated select box with the name of the post that is
 * assigned as the translation when the page loads. This value is then submitted as part of
 * a general "POST" request when the page/post is updated. WPML looks for this value and,
 * when it finds it, it uses it as the translation.
 *
 * What this means is that on some posts, our new translation ID is sent, the translation is updated,
 * but then half a second later the "icl_translation_of" value is received by WPML, which resets the translation to its prior value.
 *
 * Our solution to this is to remove the WPML metabox from the DOM if we find it.
 * It would be better to remove the WPML panel completely using a WordPress API but this doesn't seem to be possible.
 */
const _removeWPMLPanel = () => {
    const wpmlPanel = document.querySelector('#icl_div');
    if(wpmlPanel) {
        wpmlPanel.parentNode.removeChild(wpmlPanel);
    }
}

export const PageSelect = () => {
    const { isSavingPost } = select( 'core/editor' );

    const emptyPage = { is_translated: null, value: 0, label: __("None", "cds-wpml-mods") };
    // get the current post id
    const postID = select("core/editor").getCurrentPostId();
    const type = select("core/editor").getCurrentPostType();

    const hintTexts = {
        'empty': () => `${__('No translation assigned for this', "cds-wpml-mods")} ${type}.`,
        'untranslated': (label) => `“${label}” ${__('will be assigned as translation for this', "cds-wpml-mods")} ${type}.`,
        'translated': (label) => `${__('⚠️ This will unlink the existing translation for', "cds-wpml-mods")} “${label}”.`,
        'translated_post_id': (label) => `“${label}” ${__('is the current translation for this', "cds-wpml-mods")} ${type}.`,
    }

    const [isLoading, setIsLoading] = useState(true);
    const [post, setPost] = useState();
    const [pages, setPages] = useState([emptyPage]);
    const [page, setPage] = useState(emptyPage);
    const [hintText, setHintText] = useState(hintTexts['empty']());
    const [isSavingProcess, setSavingProcess] = useState(false);

    /* Triggers when the "publish" or "update" button is clicked */
    /* https://github.com/WordPress/gutenberg/issues/17632#issuecomment-1153888435 */
    subscribe(() => {
        if (isSavingPost()) {
            setSavingProcess(true);
        } else {
            setSavingProcess(false);
        }
    });

    const updatePost = async () => {
        setIsLoading(true)

        let args = {
            endpoint: `cds/wpml/posts/${postID}/translation`,
            data: { translationId: page.value },
            method: 'POST'
        }

        if(!page.value) {
            delete args.data;
            args.method = 'DELETE';
        }

        const response = await sendData(args)

        if (response.ID) {
            setPost(response)
            setIsLoading(false)
        }
    };

    const sortPages = (pages) => {
        const sortByPostTitle = (p1, p2) => (p1.post_title > p2.post_title) ? 1 : -1;

        const untranslatedPages = pages.filter(p => !p.is_translated).sort(sortByPostTitle)
        const translatedPages = pages.filter(p => p.is_translated).sort(sortByPostTitle)

        // return all pages in one array: untranslated pages first, sorted by title, then translated pages sorted by title
        return [...untranslatedPages, ...translatedPages]
    }

    useEffect(() => {
        const getPost = async (postID) => {
            const response = await getData({ endpoint: `cds/wpml/posts/${postID}/translation` });

            if (response.ID) {
                setPost(response)
            }
        }

        getPost(postID);
    }, [postID]);

    useEffect(() => {
        const getPages = async (post) => {
            const altLanguage = post.language_code === 'en' ? 'fr' : 'en';
            const response = await getData({ endpoint: `cds/wpml/${post.post_type}s/${altLanguage}` });

            if (response.length >= 1) {

                const _pages = sortPages(response).map(val => {
                        return {
                        is_translated: val.is_translated,
                        value: val.ID,
                        label: val.post_title
                    }
                })

                setPages([emptyPage, ..._pages])

                // if post has a translated_post_id, set the page to that one
                if(post.translated_post_id) {
                    setPage(_pages.find(p => p.value === post.translated_post_id))
                }
            }

            setIsLoading(false);
        }

        if(post && post.language_code) {
            getPages(post);
        }
    }, [post]);

    useEffect(() => {
        let hintTextIndex = page.is_translated === null ? 'empty' : page.is_translated === true ? 'translated' : 'untranslated';
        if(post && post.translated_post_id === page.value) {
            hintTextIndex = 'translated_post_id'
        }
        setHintText(hintTexts[hintTextIndex](page.label))

        _removeWPMLPanel()
    }, [page, post, hintTexts]);

    useEffect(() => {
        if (isSavingProcess) {
            updatePost();
        }
    }, [isSavingProcess]);

    return (
        <div>
            <SelectControl 
                label={__('Assigned translation', "cds-wpml-mods")}
                disabled={isLoading ? true : false}
                value={page.value}
                help={hintText}
                onChange={(value) => {
                    // "value" of selected option is returned as a string (our 'pages' array contains integers)
                    const _selectedPage = pages.find(p => parseInt(value) === p.value)
                    setPage(_selectedPage)
                }}
            >
                <option value={emptyPage.value}>
                    {emptyPage.label}
                </option>
                {/* make sure there is at least one untranslated post */}
                {pages.find(p => p.is_translated === false) &&
                    <optgroup label={__("Untranslated", "cds-wpml-mods")}>
                        {pages.map(p => {
                            // strictly check for false here because this is "null" on our empty value
                            if(p.is_translated === false) {
                                return <option key={p.value} value={p.value}>{p.label}</option>
                            }
                        })}
                    </optgroup>
                }
                {/* make sure there is at least one translated post */}
                {pages.find(p => p.is_translated) &&
                    <optgroup label={__("Has translation", "cds-wpml-mods")}>
                        {pages.map(p => {
                            if(p.is_translated) {
                                return <option key={p.value} value={p.value}>{p.label}</option>
                            }
                            })}
                    </optgroup>
                }

            </SelectControl>
        </div>
    );
};
