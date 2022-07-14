/**
 * External dependencies
 */
 import { __ } from "@wordpress/i18n";

 /**
 * Internal dependencies
 */
import { StyledLink, Back } from '../components';

export const NotAuthorized = () => {
    return (
        <>
            <StyledLink to={`/messages`}>
                <Back /> <span>{__("Back to messages ", "gc-lists")}</span>
            </StyledLink>
            <h1>{__("Not authorized", "gc-lists")}</h1>
            <p>{__("Sorry, you are not allowed to access this page.", "gc-lists")}</p>
        </>
    )
}
