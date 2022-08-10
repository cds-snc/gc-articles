export const parseMetaKey = (metaKey) => {
    let arr = metaKey.split(":");
    let key = metaKey;
    let prop = "";
    if (arr.length === 2) {
        key = arr[0];
        prop = arr[1];
    }

    return { key, prop }
}

export const updateValue = (metaKey, newValue, currentValue) => {

    const { prop } = parseMetaKey(metaKey);

    if (!prop) {
        return newValue;
    }

    try {
        let data = {};
        if (currentValue !== "") {
            data = JSON.parse(currentValue);
        }

        data[prop] = newValue;
        return JSON.stringify(data);
    } catch (err) {
        // no-op
        console.log(err.message);
    }
}

export const getValue = (metaKey, currentValue) => {
    const { key, prop } = parseMetaKey(metaKey);

    if (!prop) {
        return currentValue;
    }


    try {
        const data = JSON.parse(currentValue);
        return data[prop] ? data[prop] : "";
    } catch (err) {
        console.log(err.message);
        return "";
    }

}


