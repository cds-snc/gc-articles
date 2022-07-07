/**
 * External dependencies
 */
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
    const sentText = __("The message was sent to the the subscribers in the list below.", "gc-lists");
    const sendingText = <strong>{__("Sending to:", "gc-lists")}</strong>;
    return (
        <div>
            <div>{sending ? sendingText : sentText}</div>
            <StyledStats><strong>{name}</strong> {sprintf("(%s subscribers)", count, "gc-lists")}</StyledStats>
            <div>
                <p>{sprintf(
                    __( "Subscriber list created on %s at %s.", "gc-lists"),
                    "2022/04/20", // TODO: get the actual values
                    "19:56"
                )}</p>
            </div>
        </div>
    )
}
