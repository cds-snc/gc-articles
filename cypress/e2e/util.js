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

export const addChecklistRule = ({ required = false }) => {
    const rule = required ? { label: 'Required rule', type: 'required' } : { label: 'Recommended rule', type: 'recommended' }

    cy.get('#pp-checklists-add-button').click()
    cy.get('.pp-checklists-requirement-row').last().as('newRow')
    cy.get('@newRow').find('input[type="text"]').first().click().type(rule.label);
    cy.get('@newRow').find('.select2-container').first().click().get('.select2-search--dropdown').type(`${rule.type}{enter}`);
    cy.get('@newRow').find('.pp-checklists-task-params .select2-container').first().click().type('gc admin{enter}');
}
