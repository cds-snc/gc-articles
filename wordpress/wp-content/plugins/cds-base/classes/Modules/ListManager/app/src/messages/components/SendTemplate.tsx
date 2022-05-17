import * as React from 'react';
import { useState, useEffect } from 'react'
import { __ } from "@wordpress/i18n";

import { MessagePreview } from "./MessagePreview";
import { MessageSent } from "./MessageSent";
import { SendToList } from './SendToList';
import { SendingError } from './SendingError';
import { CreateNewList } from "./CreateNewList";
import { Spinner } from '../../common/Spinner';
import { useList } from "../../store/ListContext";
import { List } from '../../types';
import { useListFetch } from '../../store/UseListFetch';
import { StyledSelect } from "../editor/Styles"
import useSendTemplate from '../../store/useSendTemplate';
import useTemplateApi from '../../store/useTemplateApi';

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
    const [content, setContent] = useState<string>();
    const [name, setName] = useState<string>();
    const [subject, setSubject] = useState<string>();
    const [listId, setListId] = useState<string>();
    const [subscriberCount, setSubscriberCount] = useState<number>(0);
    const { state: { lists } } = useList();
    const { sendTemplate, success, errors } = useSendTemplate({ listId, content });
    const { templateId, getTemplate } = useTemplateApi();

    useEffect(() => {
        const loadTemplate = async () => {
            if (templateId) {
                const template = await getTemplate(templateId);
                setContent(template.content);
                setSubject(template.subject)
            }
        }
        loadTemplate();
    }, [templateId, getTemplate]);

    useEffect(() => {
        const listData = lists.filter((list: any) => list.id === listId)[0];
        const subscriberCount = listData?.subscriber_count || 0;
        setName(listData?.name);
        setSubscriberCount(Number(subscriberCount));
    }, [listId, lists]);

    if (status === "loading") {
        return <Spinner />
    }

    if (errors) {
        return <SendingError />
    }

    if (success) {
        return <MessageSent name={name} count={subscriberCount} />
    }

    return (
        <>
            <h1>{__("Send message to a list", "cds-snc")}</h1>
            <p><strong>{__("Subscriber list", "cds-snc")}</strong></p>
            <p>{__("Choose a group to send this message to.", "cds-snc")}</p>
            {lists.length >= 1 && <ListSelect lists={lists} handleChange={(val: string) => {
                setListId(val)
            }} />}

            <SendToList sending={true} name={name} count={subscriberCount} />
            <button style={{ marginRight: "20px" }} className="button button-primary" onClick={sendTemplate}>{__("Send message", "cds-snc")}</button>
            <button className="button">{__("Cancel")}</button>
            <CreateNewList />
            <MessagePreview subject={subject} content={content} />
        </>)
}

export default SendTemplate;