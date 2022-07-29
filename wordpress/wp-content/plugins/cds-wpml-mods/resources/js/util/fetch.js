const CDS_VARS = window.CDS_VARS || {
    rest_url: 'http://localhost/wp-json/',
    local: true
};

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
