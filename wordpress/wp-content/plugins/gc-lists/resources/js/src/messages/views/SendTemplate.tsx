/**
 * External dependencies
 */
import * as React from 'react';
import { useState, useEffect } from 'react'
import { __ } from "@wordpress/i18n";
import styled from 'styled-components';
import { useLocation, useNavigate } from 'react-router-dom';

/**
 * Internal dependencies
 */
import {
    MessagePreview,
    MessageSent,
    SendToList,
    SendingError,
    CreateNewList,
    ConfirmSend,
    StyledLink,
    Spinner,
    Warn,
    Next,
    Back
} from "../components";
import { List } from '../../types';
import { StyledSelect } from "../editor"
import { useList, useListFetch, useTemplateApi } from '../../store';

const StyledNext = styled.span`
    margin-left: 10px;
    position: relative;
    top: 3px;
`;

const ListSelect = ({ lists, handleChange }: { handleChange: (val: string) => void, lists: List[] }) => {
    return (
        <StyledSelect name="lists" id="lists" onChange={(evt) => {
            handleChange(evt.target.value);
        }}>
            <option key="none" value="">Select a list</option>
            {
                lists.map((list) => {
                    return <option key={list.id} value={list.id}>{list.name}</option>
                })
            }
        </StyledSelect>
    )
}

export const SendTemplate = () => {
    const { status } = useListFetch();
    const { state: { lists, hasLists } } = useList();
    const [listId, setListId] = useState<string>();
    const [subscriberCount, setSubscriberCount] = useState<number>(0);
    const [name, setName] = useState<string>("");
    const [listName, setListName] = useState<string>("");
    const [subject, setSubject] = useState<string>("");
    const [content, setContent] = useState<string>("");
    const [messageSent, setMessageSent] = useState<boolean>(false);
    const [messageSendingErrors, setMessageSendingErrors] = useState<string>("");
    const { template, templateId, getTemplate, recordSent } = useTemplateApi();

    const navigate = useNavigate();

    // @ts-ignore
    const { state = {} } = useLocation();
    // @ts-ignore
    const editorName = state?.name || "";
    // @ts-ignore
    const editorSubject = state?.subject || "";
    // @ts-ignore
    const editorTemplate = state?.template || "";

    // console.log("editorName:", editorName, "editorSubject:", editorSubject, "editorTemplate:", editorTemplate, "content:", content, "subject:", subject, "name:", name)

    useEffect(() => {
        setContent(template?.body);
        setSubject(template?.subject);
        setName(template?.name);
    }, [template]);

    useEffect(() => {
        if (editorTemplate !== "") {
            setContent(editorTemplate)
            setSubject(editorSubject)
            setName(editorName)
        } else {
            getTemplate(templateId);
        }
    }, [templateId, getTemplate, editorTemplate, editorSubject, editorName]);

    useEffect(() => {
        const listData = lists.filter((list: any) => list.id === listId)[0];
        const subscriberCount = listData?.subscriber_count || 0;
        setListName(listData?.name);
        setSubscriberCount(Number(subscriberCount));
    }, [listId, lists]);

    if (status === "loading") {
        return <Spinner />
    }

    if (messageSendingErrors) {
        return <SendingError />
    }

    if (messageSent) {
        return <MessageSent id={templateId} listName={listName} count={subscriberCount} />
    }

    return (
        <>
            {templateId !== 'new' && <StyledLink to={`/messages/edit/${templateId}`}>
                <Back /> <span>{__("Back to edit message ", "gc-lists")}</span>
            </StyledLink>}

            <h1>{__("Send message to a list", "gc-lists")}</h1>

            {hasLists ?
                <>
                    <p><strong>{__("Subscriber list", "gc-lists")}</strong></p>
                    <p>{__("Choose a group to send this message to.", "gc-lists")}</p>
                </> :
                <Warn message={__("You don't have any subscriber list.", "gc-lists")} />}

            {hasLists ?
                <>
                    <ListSelect lists={lists} handleChange={(val: string) => {
                        setListId(val)
                    }} />
                    <SendToList sending={true} name={listName} count={subscriberCount} />

                    <button
                        style={{ marginRight: "20px" }}
                        className="button button-green"
                        disabled={!content}
                        onClick={async () => {
                            const confirmed = await ConfirmSend({ count: subscriberCount });
                            if (confirmed) {
                                const result = await recordSent(templateId, listId, listName, name, subject, content);
                                if (result) {
                                    navigate(`/messages/send/${result?.id}`);
                                    setMessageSent(true);
                                } else {
                                    // @TODO: get errors from api call
                                    setMessageSendingErrors("There was an error");
                                }
                            }
                        }}>
                        {__("Send message", "gc-lists")}

                        <StyledNext><Next color={"#fff"} /></StyledNext>
                    </button>
                    <button className="button" onClick={() => {
                        navigate(`/messages`);
                    }}>{__("Cancel")}</button>
                </>
                : null
            }
            <CreateNewList />
            <h2>{__("Message preview", "gc-lists")}</h2>
            <MessagePreview subject={subject} content={content} />
        </>)
}

export default SendTemplate;
