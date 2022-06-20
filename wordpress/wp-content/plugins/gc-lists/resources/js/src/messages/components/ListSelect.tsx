
/**
 * External dependencies
 */
import { useCallback, useEffect, useState } from 'react';
import { __ } from "@wordpress/i18n";
import useFetch from 'use-http';

/**
 * Internal dependencies
 */

import { useList, useService } from '../../store';
import { List, ListType } from '../../types';
import { StyledSelect } from "../editor";
import { Warn, Spinner } from "../components";
import { getListType } from "../../util/functions";

const Select = ({ lists, handleChange }: { handleChange: (val: string) => void, lists: List[] }) => {

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

export const ListSelect = ({ onChange }: { onChange: any }) => {
    const { serviceId } = useService();
    const { state: { user, config: { listManagerApiPrefix } } } = useList();
    const { request, response } = useFetch(listManagerApiPrefix, { data: [] })
    const [lists, setLists] = useState<List[]>([]);


    useEffect(() => {
        const getLists = async () => {
            await request.get(`/lists/${serviceId}`);

            if (response.ok) {
                let lists = response.data;



                lists = lists.filter((list: List) => {
                    const listType = getListType(list.language)
                    if (listType === ListType.EMAIL && user?.hasEmail) {
                        return true
                    }
                    if (listType === ListType.PHONE && user?.hasPhone) {
                        return true
                    }
                    return false
                });

                setLists(lists)
            }
        }

        getLists();

    }, [request, response, serviceId, user?.hasEmail, user?.hasPhone])

    const handleChange = useCallback((listId: string) => {
        if (lists) {
            const listData = lists.filter((list: any) => list.id === listId)[0];
            const subscriberCount = listData?.subscriber_count || 0;
            onChange({ listId: listId, listName: listData?.name, count: Number(subscriberCount) })
        }
    }, [lists, onChange]);

    if (lists?.length < 1) {
        return <Spinner />
    }

    return lists?.length >= 1 ?
        <>
            <p><strong>{__("Subscriber list", "gc-lists")}</strong></p>
            <p>{__("Choose a group to send this message to.", "gc-lists")}</p>

            <Select lists={lists} handleChange={(val: string) => {
                handleChange(val)
            }} />
        </> :
        <Warn message={__("You don't have any subscriber list.", "gc-lists")} />
}