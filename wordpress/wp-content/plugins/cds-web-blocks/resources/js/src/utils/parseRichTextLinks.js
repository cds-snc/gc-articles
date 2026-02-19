function stripSlashes(str) {
    return str.replace(new RegExp("\\\\", "g"), "");
}

function parseTag(str) {
    const link = str.trim();
    const linkRx = /<a\s+(?:[^>]*?\s+)?href=(["'])(.*?)\1/;
    const href = link.match(linkRx)[2];
    const text = link.match(/<a [^>]+>([^<]+)<\/a>/)[1];
    return { link: href, text };
}

export const parseRichTextLinks = (str) => {
    const links = stripSlashes(str).split("<br>");
    return links.map(link => parseTag(link));
}