import * as React from 'react';
import { useCallback, useState } from 'react'
import useFetch from 'use-http';
import { Editor } from "./Editor";
import { useList } from "../store/ListContext";
import { List } from '../types';
import { StyledSelect } from "./editor/Styles"
import { Spinner } from './Spinner';
import { useListFetch } from '../store/UseListFetch';

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
    const { request, response } = useFetch({ data: [] });
    const { status } = useListFetch();
    const [listId, setListId] = useState<String>("");
    const { state } = useList();
    const { lists } = state;

    const send = useCallback(async (data: string) => {
        let endpoint = "/send"
        let post_data = {
            list_id: listId,
            template_id: '40454604-8702-4eeb-9b38-1ed3104fb960', // @todo this will come form WP
            template_type: 'email',
            job_name: 'el-jobbo',
            personalisation: JSON.stringify({ message: data, subject: 'Huzzah!' }),
        }

        await request.post(endpoint, post_data)

        console.log(response)
    }, [response, request, listId]);

    if (status === "loading") {
        return <Spinner />
    }

    return (
        <>
            {/* @todo add subject */}
            {lists.length >= 1 && <ListSelect lists={lists} handleChange={(val: string) => {
                setListId(val)
            }} />}
            <Editor onSend={send} />
        </>)
}

export default SendTemplate;