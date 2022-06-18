/**
 * External dependencies
 */
import * as React from 'react';
import { useState, useEffect } from 'react';
import styled from 'styled-components';
import { __ } from "@wordpress/i18n";
import { useParams } from "react-router-dom";
import useFetch from 'use-http';

/**
 * Internal dependencies
 */
import { StyledH1, Spinner, MessagePreview } from "../components";

const StyledTag = styled.span`
    display: inline-block;
    background-color: #efefef;
    padding:2px 10px;
    margin-right:10px;
    font-size:12px;
`

const SuccessTag = styled.span`
    display: inline-block;
    background-color: #B3DFC0;
    padding:2px 10px;
    margin-right:10px;
    font-size:12px;
`

const Tag = ({ text }: { text: string }) => {
    return <StyledTag>{text}</StyledTag>
}

const SentTag = ({ text }: { text: string }) => {
    return <SuccessTag>{text}</SuccessTag>
}

const Status = ({ item }: { item: any }) => {
    const { original_message_id, sent_at, created_at, updated_at } = item;
    if (sent_at) {
        return <><SentTag text={__("SENT", "gc-lists")} /><span>{__("Sent on", "gc-lists")} {sent_at}, </span></>
    }

    if (original_message_id !== null) {
        return <><Tag text={__("EDITED", "gc-lists")} /><span>{__("Edited on", "gc-lists")} {updated_at}, </span></>
    }

    return <><Tag text={__("CREATED", "gc-lists")} /><span>{__("Created on", "gc-lists")} {created_at}, </span></>
}

const StyledDetails = styled.div`
    font-size:16px;
    margin-top:20px;
    margin-bottom:20px;
`

const StyledStatus = styled(Status)`
    margin-top:20px;
`

export const Versions = () => {
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);
    const { request, response } = useFetch({ data: [] })
    const params = useParams();

    useEffect(() => {
        const getTemplateVersions = async () => {
            setLoading(true);
            const messageId = params?.messageId;
            await request.get(`/messages/${messageId}/versions`);
            if (response.ok) {
                setData(response.data);
                setLoading(false)
            }
        }
        getTemplateVersions();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    return (
        <div>
            <StyledH1> {__("Previous versions", "gc-lists")} </StyledH1>
            <>
                {data?.length > 0 ? data.map((item) => {
                    const { sent_by_email, id, body, subject } = item;
                    return (
                        <div key={id}>
                            <h2>{subject}</h2>
                            <StyledDetails>
                                <StyledStatus item={item} />
                                {sent_by_email && <>{__("by", "gc-lists")} <span>{sent_by_email}</span></>}
                            </StyledDetails>
                            <MessagePreview content={body} subject={subject} />
                        </div>
                    )
                }) : loading && <Spinner />}
            </>
        </div>
    )
}

export default Versions;