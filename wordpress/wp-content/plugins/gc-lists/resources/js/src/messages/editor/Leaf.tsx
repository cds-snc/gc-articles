/**
 * External dependencies
 */
import * as React from 'react';

import { StyledSpan } from "./Styles";

// @ts-ignore
export const Leaf = ({ attributes, children, leaf }) => {
    return (
        <StyledSpan {...attributes} leaf={leaf}>
            {children}
        </StyledSpan >
    );
};