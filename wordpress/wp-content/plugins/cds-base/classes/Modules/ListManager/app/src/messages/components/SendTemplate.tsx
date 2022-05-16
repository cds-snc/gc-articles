import * as React from 'react';
import { useState, useEffect } from 'react'
import { __ } from "@wordpress/i18n";

import { MessagePreview } from "./MessagePreview";
import { MessageSent } from "./MessageSent";
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
    const [content, setContent] = useState<String>();
    const [listId, setListId] = useState<String>();
    const { state: { lists } } = useList();
    const { sendTemplate, success, errors } = useSendTemplate({ listId, content });
    const { templateId, getTemplate } = useTemplateApi();

    useEffect(() => {
        const loadTemplate = async () => {
            if (templateId) {
                const template = await getTemplate(templateId);
                setContent(template.content);
            }
        }
        loadTemplate();
    }, [templateId, getTemplate]);

    if (status === "loading") {
        return <Spinner />
    }

    if (errors) {
        return <SendingError />
    }

    if (success) {
        return <MessageSent count={201} />
    }

    return (
        <>
            <h1>{__("Send message to a list", "cds-snc")}</h1>
            <p><strong>{__("Subscriber list", "cds-snc")}</strong></p>
            <p>{__("Choose a group to send this message to.", "cds-snc")}</p>
            {lists.length >= 1 && <ListSelect lists={lists} handleChange={(val: string) => {
                setListId(val)
            }} />}
            <button style={{ marginRight: "20px" }} className="button button-primary" onClick={sendTemplate}>{__("Send Email")}</button>
            <button className="button">{__("Cancel")}</button>
            <CreateNewList />
            <MessagePreview />
        </>)
}

export default SendTemplate;