import * as React from 'react';
import { useState } from 'react'

import { Spinner } from '../../common/Spinner';
import { useList } from "../../store/ListContext";
import { List } from '../../types';
import { useListFetch } from '../../store/UseListFetch';
import { StyledSelect } from "../editor/Styles"
import { Editor } from "../editor/Editor";
import useSendTemplate from '../editor/useSendTemplate';

const ListSelect = ({ lists, handleChange }: { handleChange: (val: string) => void, lists: List[] }) => {
    // @todo -- add subscriber_count
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
    const [listId, setListId] = useState<String>("");
    const { state: { lists } } = useList();
    const sendTemplate = useSendTemplate(listId);

    if (status === "loading") {
        return <Spinner />
    }

    return (
        <>
            {/* @todo add subject */}
            {lists.length >= 1 && <ListSelect lists={lists} handleChange={(val: string) => {
                setListId(val)
            }} />}
            <Editor />
            <button className="button" onClick={sendTemplate}>Send Email</button>
        </>)
}

export default SendTemplate;