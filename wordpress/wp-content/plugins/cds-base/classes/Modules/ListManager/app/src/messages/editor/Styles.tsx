// @ts-nocheck 
import styled, { css } from 'styled-components';

export const Label = styled.label`
    font-size: 19px;
    line-height: 1.25;
`

export const StyledSelect = styled.select`
    display:block;
    margin-bottom:20px;
    min-width:250px;
`

export const TextWrapper = styled.div`
 max-width:700px;
 margin-bottom:20px;
 min-height: 258px;

 & > div[role=textbox]{
    border: 1px solid #1d2327;
    border-radius: 4px;
    padding:10px;
    width:700px;
    height:100%;
    min-height: 258px;

    &:focus {
        border: 1px solid #1d2327 !important;
        outline: 2px solid #0535d2 !important;
     }
 }
 
 `

export const StyledSpan = styled.span`
    ${props => props.leaf.bold && css`
        font-weight: bold;
    `}

    ${props => props.leaf.tag && css`
        background-color: #ffbf47;
    `}

    ${props => props.leaf.italic && css`
        font-style: italic;
    `}

    ${props => props.leaf.underlined && css`
        text-decoration: underline;
    `}

    ${props => props.leaf.title && css`
        display: inline-block;
        font-weight: bold;
        font-size: 20px;
        margin: 20px 0 10px 0;
    `}

    ${props => props.leaf.list && css`
        padding-left: 10px;
        font-size: 20px;
        line-height: 10px;
    `}

    ${props => props.leaf.hr && css`
        display: block;
        text-align: center;
        border-bottom: 2px solid #000;
    `}

    ${props => props.leaf.blockquote && css`
        display: inline-block;
        border-left: 2px solid #ddd;
        padding-left: 10px;
        color: #aaa;
        font-style: italic;
    `}
 `