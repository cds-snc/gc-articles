/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { Back, StyledLink } from '../../common';
import { ListDrafts } from '../components';

export const AllDrafts = () => {
    return (
        <>
            <StyledLink to={`/messages`}>
                <Back /> <span>{__("Back to messages ", "gc-lists")}</span>
            </StyledLink>
            <ListDrafts perPage={10} pageNav={true} />
        </>
    )
}

export default AllDrafts;
