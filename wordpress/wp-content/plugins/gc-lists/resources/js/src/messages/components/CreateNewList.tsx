import { __ } from "@wordpress/i18n";
import { Link } from "react-router-dom";
import styled from 'styled-components';

const StyledMessage = styled.div`
 margin-top:60px;
 margin-bottom:60px;
`;

export const CreateNewList = () => {
    return (
        <StyledMessage>
            <p>
                <strong>
                    <Link to={`/list/create`}>{__("Create a new list.", "cds-snc")}</Link></strong> <br />
                {__("A subscriber list allows you to collect a group of subscribers that you can send a message to.", "cds-snc")}
            </p>
        </StyledMessage>
    )
}
