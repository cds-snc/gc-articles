const { SelectControl, SelectControlWithState, TreeSelect } = wp.components;
const { useState, useEffect } = wp.element;
const { __ } = wp.i18n;


/* fetch.ts */

/*

'rest_url' => esc_url_raw(rest_url()),
'rest_nonce' => wp_create_nonce('wp_rest'),
'notify_list_ids' => getNotifyListIds(),

// needs typescript
export interface ErrorWithStatus extends Error {
    status: number;
}
*/

const CDS_VARS = {
    'rest_url': 'http://localhost/wp-json/',
   // 'rest_nonce': '123',
}

export const getData = async (endpoint) => {
const requestHeaders = new Headers();
// do we need this?
//requestHeaders.append('X-WP-Nonce', CDS_VARS.rest_nonce);

const response = await fetch(`${CDS_VARS.rest_url}${endpoint}`, {
    method: 'GET',
    headers: requestHeaders,
    mode: 'cors',
    cache: 'default',
});

if (!response.ok) {
    console.log(response.body);
    const err = new Error(`HTTP error`);
    err.status = response.status;
    throw err;
}

return await response.json();
};

export const sendData = async (endpoint, data) => {
const requestHeaders = new Headers({
    'Content-Type': 'application/json;charset=UTF-8',
});
//requestHeaders.append('X-WP-Nonce', CDS_VARS.rest_nonce);

const response = await fetch(`${CDS_VARS.rest_url}${endpoint}`, {
    method: 'POST',
    headers: requestHeaders,
    mode: 'cors',
    cache: 'default',
    body: JSON.stringify(data),
});

if (!response.ok) {
    console.log(response.body);
    const err = new Error(`HTTP error`);
    err.status = response.status;
    throw err;
}

return await response.json();
};

/* end of fetch.ts */

const myPages = [
    {
        "ID": 49,
        "post_title": "Post: 49 (FR)",
        "post_type": "post",
        "language_code": "fr",
        "translated_post_id": 45,
        "is_translated": true
    },
    {
        "ID": 47,
        "post_title": "Post: 47 (FR)",
        "post_type": "post",
        "language_code": "fr",
        "translated_post_id": 53,
        "is_translated": true
    },
    {
        "ID": 32,
        "post_title": "Post: Charles-Maurice de Talleyrand-Périgord (FR)",
        "post_type": "post",
        "language_code": "fr",
        "translated_post_id": null,
        "is_translated": false
    },

    {
        "ID": 50,
        "post_title": "XYZ Translated",
        "post_type": "post",
        "language_code": "fr",
        "translated_post_id": 500,
        "is_translated": true
    },
    {
        "ID": 51,
        "post_title": "ABC Translated",
        "post_type": "post",
        "language_code": "fr",
        "translated_post_id": 510,
        "is_translated": true
    },
    {
        "ID": 52,
        "post_title": "Joe Strummer",
        "post_type": "post",
        "language_code": "fr",
        "translated_post_id": null,
        "is_translated": false
    },
    {
        "ID": 53,
        "post_title": "Zelda Fitzgerald",
        "post_type": "post",
        "language_code": "fr",
        "translated_post_id": null,
        "is_translated": false
    }
]



export const PageSelect = () => {
    const emptyPage = { is_translated: null, value: 0, label: __("No translation", "cds-snc") };

    const hintTexts = {
        'empty': () => __("No translation assigned for this post.", "cds-wpml-mods"),
        'untranslated': (label) => `“${label}” ${__('assigned French-language translation for this post.', "cds-wpml-mods")}`,
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

                // const _pages = sortPages(response).map(val => {
                const _pages = sortPages(myPages).map(val => {
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
                label={__("Options Select Name", "cds-wpml-mods")}
                disabled={isLoading ? true : false}
                value={page.value}
                help={hintText}
                onChange={(selectedPageID) => {
                    // "value" of selected option is returned, as a string (our 'pages' array) contains integers

                    const _selectedPage = pages.find(p => parseInt(selectedPageID) === p.value)
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
