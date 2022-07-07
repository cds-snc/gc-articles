/**
 * External dependencies
 */
import { useState, useEffect, useCallback } from 'react'
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
    Next,
    Back,
} from "../components";

import { ListSelect } from "../components";

import { useTemplateApi } from '../../store';

const StyledNext = styled.span`
    margin-left: 10px;
    position: relative;
    top: 3px;
`;


export const SendMessage = () => {
    const [name, setName] = useState<string>("");
    const [subject, setSubject] = useState<string>("");
    const [content, setContent] = useState<string>("");
    const [messageSent, setMessageSent] = useState<boolean>(false);
    const [messageSendingErrors, setMessageSendingErrors] = useState<string>("");

    const [listId, setListId] = useState<string>();
    const [listName, setListName] = useState<string>("");
    const [listType, setListType] = useState<string>("");
    const [subscriberCount, setSubscriberCount] = useState<number>(0);

    const handleListUpdate = useCallback(({ listId, listName, count, listType }: { listId: string, listName: string, count: number, listType: string }) => {
        setListId(listId);
        setListName(listName);
        setSubscriberCount(count);
        setListType(listType);
    }, [])

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

    if (messageSendingErrors) {
        return <SendingError />
    }

    if (messageSent) {
        return <MessageSent id={templateId} listName={listName} count={subscriberCount} />
    }

    return (
        <>
            {content && templateId !== 'new' && <StyledLink to={`/messages/edit/${templateId}`}>
                <Back /> <span>{__("Edit message", "gc-lists")}</span>
            </StyledLink>}
            {/* TODO: If a new message, edit? */}
            {!content && <StyledLink to={`/messages`}>
                <Back /> <span>{__("Back to messages ", "gc-lists")}</span>
            </StyledLink>}

            <h1>{__("Send message to a list", "gc-lists")}</h1>

            <ListSelect onChange={handleListUpdate} />
            {listId ?
                <>
                    <SendToList sending={true} name={listName} count={subscriberCount} />
                    <button
                        style={{ marginRight: "20px" }}
                        className="button button-green"
                        disabled={!content}
                        onClick={async () => {
                            const confirmed = await ConfirmSend({ count: subscriberCount });
                            if (confirmed) {
                                const result = await recordSent(templateId, listId, listName, listType, name, subject, content);
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
            {content &&
                <>
                    <h2>{__("Message preview", "gc-lists")}</h2>
                    <MessagePreview subject={subject} content={content} />
                </>
            }
        </>)
}

export default SendMessage;
