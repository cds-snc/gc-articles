/**
 * External dependencies
 */
import * as React from 'react';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { ListTemplates, StyledLink, Back } from '../components';

export const AllTemplates = () => {
    return (
        <>
            <StyledLink to={`/messages`}>
                <Back /> <span>{__("Back to messages ", "gc-lists")}</span>
            </StyledLink>
            <ListTemplates perPage={10} pageNav={true} />
        </>
    )
}

export default AllTemplates;
