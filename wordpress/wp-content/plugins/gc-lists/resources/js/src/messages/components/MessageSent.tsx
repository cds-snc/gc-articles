/**
 * External dependencies
 */
import * as React from 'react';
import { __ } from "@wordpress/i18n";
import styled from 'styled-components';
import { Link } from "react-router-dom";

/**
 * Internal dependencies
 */
import { SendToList } from ".";

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

export const MessageSent = ({ id, listName, count }: { id: string | undefined, listName: string | undefined, count: number }) => {
    return (
        <>
            <StyledSuccess>
                <div><h2>{__("Message sent", "gc-lists")}</h2></div>
            </StyledSuccess>
            <SendToList sending={false} name={listName} count={count} />
            <StyledActionContainer>
                <Link
                    to={`/messages/edit/${id}`}
                    style={{ marginRight: "20px" }}
                    className="button button-primary"
                >
                    {__("Send a new message", "gc-lists")}
                </Link>
                <Link
                    to={`/messages`}
                    className="button"
                >
                    {__("Return to messages", "gc-lists")}
                </Link>
            </StyledActionContainer>
        </>
    )
}
