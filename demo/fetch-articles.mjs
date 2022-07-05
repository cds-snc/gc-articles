import fetch from 'node-fetch';
import slugify from 'slugify';
import { writeFile } from 'fs/promises';

const ENDPOINT = "https://articles.alpha.canada.ca/cds-snc/wp-json/wp/v2/posts?markdown=true&_embed";

const extractData = (post) => {
    let out = "";
    const title = post.title.rendered;
    out += "---\n";
    out += "layout: blog\n";
    out += "title: '" + title + "'\n";
    out += "description: >-\n";
    out +=  post.markdown.excerpt.rendered + "\n";
    out += "author: '" + post._embedded.author[0].name + "'\n";
    out += "date: '" + post.modified + "'\n";

    out += "---\n";
    out +=  post.markdown.content.rendered + "\n";
    return { title: slugify(title, { lower: true, strict: true }), body: out };
}

const getBlogPosts = async () => {
    const result = await fetch(ENDPOINT);
    const data = await result.json();

    let files = [];
    for (const p in data) {
        const content = extractData(data[p]);
        await writeFile(`posts/${content.title}.md`, content.body);
    }


    return files;
}

(async function () {
    await getBlogPosts();
})();

