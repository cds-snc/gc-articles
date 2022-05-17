import { __, sprintf } from "@wordpress/i18n";
import styled from 'styled-components';

export const StyledStats = styled.div`
    display:block;
    border: 1px solid #CCCCCC;
    min-width:250px;
    padding:10px;
    margin-top:20px;
    margin-bottom:20px;
`

export const SendToList = ({ name, count, sending = true }: { name: string | undefined, count: number, sending: boolean }) => {
    const sentText = __("The message was sent to the the subscribers in the list below.", "cds-snc");
    const sendingText = <strong>{__("Sending to:", "cds-snc")}</strong>;
    return (
        <div>
            <div>{sending ? sendingText : sentText}</div>
            <StyledStats><strong>{name}</strong> {sprintf("(%s subscribers)", count, "cds-snc")}</StyledStats>
            <div><p>{sprintf("Subscriber list created on %s at %s.", "2022/04/20", "19:56", "cds-snc")}</p></div>
        </div>
    )
}