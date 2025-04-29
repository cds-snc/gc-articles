const _addNew = (url, text, { title }) => {
    cy.visit(url);

    cy.setPostContent(text);
    cy.wait(1000);
    if (title) {
        cy.get("h1.wp-block-post-title").type(title);
        cy.wait(1000);
    }
    cy.get("body").type('{cmd}s');
    cy.wait(1000);
}

export const addArticle = (text, { title = '' } = {}) => {
    _addNew("/wp-admin/post-new.php", text, { title });
}

export const addPage = (text, { title = "Title" } = {}) => {
    _addNew("/wp-admin/post-new.php?post_type=page", text, { title });
}
