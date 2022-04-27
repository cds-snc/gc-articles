// @ts-nocheck 
import styled, { css } from 'styled-components';

export const Label = styled.label`
    font-size: 19px;
    line-height: 1.25;
`

export const Hint = styled.span`
    padding-bottom: 2px;
    font-size: 19px;
    line-height: 1.25;
    font-weight: 400;
    display: block;
    color: rgb(89, 89, 89);
`

export const StyledSelect = styled.select`
    display:block;
    margin-bottom:20px;
`

export const TextWrapper = styled.div`
 max-width:823px;
 min-height:212px;
 font-size: 19px;
 margin-bottom:20px;

 & > div[role=textbox]{
    padding:10px;
    border: 2px solid #000;

    &:focus {
        box-shadow: 0 0 0 3px #ffbf47;
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