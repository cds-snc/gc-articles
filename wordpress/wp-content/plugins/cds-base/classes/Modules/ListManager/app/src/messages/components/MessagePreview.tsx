import { __ } from "@wordpress/i18n";
import styled from 'styled-components';

export const StyledSubject = styled.div`
    display:block;
    border: 1px solid #CCCCCC;
    min-width:250px;
    padding:10px;

    strong{
        display:inline-block;
        margin-right:50px;
    }
`

export const StyledPreview = styled.div`
    display:block;
    border: 1px solid #CCCCCC;
    min-width:250px;
    padding:10px;
    margin-top:20px;
    margin-bottom:20px;
    width:100%;
`

export const StyledContent = styled.div`
    width:700px;
    margin: 0 auto;
`

export const MessagePreview = ({ content, subject }: { content: string | undefined, subject: string | undefined }) => {
    return (
        <>
            <h2>{__("Message preview", "cds-snc")}</h2>
            <StyledSubject>
                <strong>{__("Subject", "cds-snc")}</strong>
                {subject}
            </StyledSubject>
            <StyledPreview>
                <StyledContent>
                    {content}
                </StyledContent>
            </StyledPreview>
        </>
    )

}