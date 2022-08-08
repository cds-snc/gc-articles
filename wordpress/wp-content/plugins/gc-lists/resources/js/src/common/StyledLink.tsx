/**
 * External dependencies
*/
import styled from 'styled-components';
import { Link } from "react-router-dom";

export const StyledLink = styled(Link)`
    position:relative;
    display:block;
    margin-top:20px;
    margin-bottom: 20px;
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