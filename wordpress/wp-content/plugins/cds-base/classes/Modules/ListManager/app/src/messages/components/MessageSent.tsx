import { __ } from "@wordpress/i18n";
import { SendToList } from "./SendToList";
import styled from 'styled-components';

const StyledActionContainer = styled.div`
    margin-top:60px;
`

const StyledSuccess = styled.div`
    background: #ccc;
    border-left: 5px solid #ccc;
    background: #d8eeca;
    border-left-color: #278400;
    padding:30px;
    h2{
        margin:0;
        padding:0;
    }
    margin-top: 30px;
    margin-bottom: 30px;
`



export const MessageSent = ({ name, count }: { name: string | undefined, count: number }) => {
    return (
        <>

            <StyledSuccess>
                <div><h2>{__("Message sent", "cds-snc")}</h2></div>
            </StyledSuccess>

            <SendToList sending={false} name={name} count={count} />
            <StyledActionContainer>
                <button style={{ marginRight: "20px" }} className="button button-primary" >{__("Send a new message", "cds-snc")}</button>
                <button className="button">{__("Return to messages", "cds-snc")}</button>
            </StyledActionContainer>
        </>
    )
}