export const addArticle = async (text) => {
    cy.visit("/wp-admin/post-new.php");
    cy.setPostContent(text);
    cy.wait(1000);
    cy.get("body").type('{cmd}s');
    cy.wait(1000);

    return new Promise((resolve, reject) => {
        cy.get(".editor-post-preview").invoke('attr', 'href').then((href) => {
            resolve(href);
        });
    })
}