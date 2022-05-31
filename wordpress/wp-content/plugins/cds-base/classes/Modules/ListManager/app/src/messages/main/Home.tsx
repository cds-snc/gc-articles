import styled from 'styled-components';
import { Link } from "react-router-dom";
import { __ } from "@wordpress/i18n";

import { useService } from '../../util/useService';
import { ListTemplates } from "../components/ListTemplates";
import { SendingHistory } from "../components/SendingHistory";
import { StyledPaging } from "../components/Table";
import { Next } from "../components/icons/Next";

const StyledLink = styled(Link)`
    position:relative;
    display:block;
    margin-top:20px;
    span{
        display:inline-block;
        text-decoration:underline !important;
        position:relative;
        top:-1px;
        margin-right:10px;

        :hover{
            text-decoration:none !important;
        }
    }
`

export const Home = () => {
    const { serviceId } = useService();
    return (
        <>
            <>
                <ListTemplates />
                <StyledPaging>
                    <StyledLink to={`/messages/${serviceId}/all-templates`} >
                        <span>{__("All message templates", "cds-snc")}</span><Next />
                    </StyledLink>
                </StyledPaging>
            </>
            <>
                <SendingHistory />
                <StyledPaging>
                    <StyledLink to={`/messages/${serviceId}/history`} >
                        <span> {__("All sending history", "cds-snc")} </span><Next />
                    </StyledLink>
                </StyledPaging>
            </>
        </>

    )
}

export default Home;