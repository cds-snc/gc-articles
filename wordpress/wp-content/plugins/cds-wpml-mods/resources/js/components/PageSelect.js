const { SelectControl } = wp.components;
const { useState, useEffect } = wp.element;
const { select } = wp.data;
const { __ } = wp.i18n;

import { getData } from '../util/fetch.js';

export const PageSelect = () => {
    const emptyPage = { is_translated: null, value: 0, label: __("None", "cds-snc") };

    const type = select("core/editor").getCurrentPostType();

    const hintTexts = {
        'empty': () => `${__('No translation assigned for this', "cds-wpml-mods")} ${type}.`,
        'untranslated': (label) => `“${label}” ${__('assigned as translation for this', "cds-wpml-mods")} ${type}.`,
        'translated': (label) => `${__('⚠️ This will unlink the existing translation for', "cds-wpml-mods")} “${label}”.`,
    }


    const [isLoading, setIsLoading] = useState(true);
    const [pages, setPages] = useState([emptyPage]);
    const [page, setPage] = useState(emptyPage);
    const [hintText, setHintText] = useState(hintTexts['empty']());

    const sortPages = (pages) => {
        const sortByPostTitle = (p1, p2) => (p1.post_title > p2.post_title) ? 1 : -1;

        const untranslatedPages = pages.filter(p => !p.is_translated).sort(sortByPostTitle)
        const translatedPages = pages.filter(p => p.is_translated).sort(sortByPostTitle)

        // return all pages in one array: untranslated pages first, sorted by title, then translated pages sorted by title
        return [...untranslatedPages, ...translatedPages]
    }

    useEffect(() => {
        const getPages = async () => {
            const response = await getData('cds/wpml/posts/fr');

            if (response.length >= 1) {

                const _pages = sortPages(response).map(val => {
                        return {
                        is_translated: val.is_translated,
                        value: val.ID,
                        label: val.post_title
                    }
                })

                setPages([emptyPage, ..._pages]);

                setIsLoading(false);
            }
        }

        getPages();
    });

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

                    const hintTextIndex = _selectedPage.is_translated === null ? 'empty' : _selectedPage.is_translated === true ? 'translated' : 'untranslated';
                    setHintText(hintTexts[hintTextIndex](_selectedPage.label))
                }}
            >
                <option value={emptyPage.value}>
                    {emptyPage.label}
                </option>
                <optgroup label={__("Untranslated", "cds-wpml-mods")}>
                    {pages.map(p => {
                        // strictly check for false here because this is "null" on our empty value
                        if(p.is_translated === false) {
                            return <option key={p.value} value={p.value}>{p.label}</option>
                        }
                    })}
                </optgroup>
                <optgroup label={__("Has translation", "cds-wpml-mods")}>
                    {pages.map(p => {
                        if(p.is_translated) {
                            return <option key={p.value} value={p.value}>{p.label}</option>
                        }
                        })}
                </optgroup>

            </SelectControl>
        </div>
    );
};
