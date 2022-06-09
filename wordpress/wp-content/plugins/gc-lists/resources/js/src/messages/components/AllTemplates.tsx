import { ListTemplates } from "./ListTemplates";
import { Back } from './icons/Back';
import { __ } from '@wordpress/i18n';
import { StyledLink } from './Table';
import * as React from 'react';

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
