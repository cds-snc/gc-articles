/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Back, StyledLink } from '../../common';
import { SendingHistory } from "../components";

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
