/**
 * External dependencies
 */
import * as React from 'react';
import { __ } from "@wordpress/i18n";
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
                    <a href="?page=gc-lists_subscribers#/lists/create">{__("Create a new list.", "gc-lists")}</a></strong> <br />
                {__("A subscriber list allows you to collect a group of subscribers that you can send a message to.", "gc-lists")}
            </p>
        </StyledMessage>
    )
}
