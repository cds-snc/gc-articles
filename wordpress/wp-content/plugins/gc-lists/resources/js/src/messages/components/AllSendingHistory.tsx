import { SendingHistory } from "./SendingHistory";
import { __ } from '@wordpress/i18n';
import { StyledLink } from './Table';
import * as React from 'react';
import { Back } from './icons/Back';

export const AllSendingHistory = () => {
    return (
        <>
            <StyledLink to={`/messages`}>
                <Back /> <span>{__("Back to messages ", "gc-lists")}</span>
            </StyledLink>
            <SendingHistory perPage={10} pageNav={true} allLink={false} />
        </>

    )
}

export default AllSendingHistory;
