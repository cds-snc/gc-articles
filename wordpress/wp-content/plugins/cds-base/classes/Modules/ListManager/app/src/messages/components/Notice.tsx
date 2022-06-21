import styled from 'styled-components';

import { Check as CheckIcon } from "./icons/Check";
import { Warn as WarnIcon } from "./icons/Warn";

const StyledNotice = styled.div`
    border-left: 5px solid #ccc;
    padding:20px;
    font-weight: normal;
    h3{
        position:relative;
        margin:0;
        padding:0;
        display:inline-block;
        top:-3px;
        margin-left:20px;
    }
    margin-top: 20px;
    margin-bottom: 20px;
`;


const StyledSuccess = styled(StyledNotice)`
    background-color: #d8eeca;
    border-left-color: #278400;
`;

const StyledWarn = styled(StyledNotice)`
    background: #fff7e6;
    border-left-color: #ee7100;
`;

export const Success = ({ message }: { message: string | undefined }) => {
    return (
        <StyledSuccess>
            <CheckIcon /><h3>{message}</h3>
        </StyledSuccess>
    )
}

export const Warn = ({ message }: { message: string | undefined }) => {
    return (
        <StyledWarn>
            <WarnIcon /> <h3>{message}</h3>
        </StyledWarn>
    )
}