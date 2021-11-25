export const addArticle = (text) => {
    cy.visit("/wp-admin/post-new.php");
    cy.setPostContent(text);
    cy.wait(1000);
    cy.get("body").type('{cmd}s');
    cy.wait(1000);
}

export const addPage = (text, title = "Title", childPage) => {
    cy.visit("/wp-admin/post-new.php?post_type=page");
    cy.setPostContent(text);
    cy.wait(1000);
    cy.get("textarea#post-title-0").type(title);
    cy.wait(1000);
    cy.get("body").type('{cmd}s');
    cy.wait(1000);
}
