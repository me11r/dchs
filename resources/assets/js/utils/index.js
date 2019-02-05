export function serialize(obj) {
    let str = [];
    for (let p in obj) {
        if (obj.hasOwnProperty(p)) {
            if (obj[p] === null ||
                obj[p] === undefined ||
                obj[p] === '' ||
                obj[p] === ' ' ||
                obj[p] === 'null') {
                delete obj[p];
            } else {
                str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]));
            }
        }
    }
    return str.join('&');
}

export function isEmpty(key) {
    if (key === null ||
        key === undefined ||
        key === '' ||
        key === ' ' ||
        key === 'null') {
        return true;
    } else {
        return false;
    }
}

export function clone(data) {
    return JSON.parse(JSON.stringify(data));
}
