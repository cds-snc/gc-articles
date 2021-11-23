import * as React from "react";
import { SVG, Path } from '@wordpress/primitives';

export const ChevronUp = () => {
    return (
        <SVG width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <Path d="M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z" />
        </SVG>)
};

export const ChevronDown = (
    <SVG width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <Path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z" />
    </SVG>
);

export const Close = (
    <SVG width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <Path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z" />
    </SVG>
);
