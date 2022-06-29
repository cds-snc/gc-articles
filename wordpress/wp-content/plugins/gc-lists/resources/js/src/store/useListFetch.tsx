/**
 * External dependencies
 */
import * as React from 'react';
import { useEffect, useState } from 'react';
import useFetch from 'use-http';

/**
 * Internal dependencies
 */
import { useList } from "./listContext";
import { List, ListType } from '../types';
import { getListType } from "../util/functions";
import { useService } from './useService';

export const useListFetch = () => {
    const { dispatch, state: { user, config: { listManagerApiPrefix } } } = useList();
    const { serviceId } = useService();
    const [status, setStatus] = useState('idle');

    const { request, response } = useFetch(listManagerApiPrefix, { data: [] })

    useEffect(() => {
        const fetchData = async () => {
            setStatus("loading")
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

                if (lists?.length === 0) {
                    dispatch({ type: "no-lists", payload: [] });
                    setStatus("idle");
                    return;
                }

                dispatch({ type: "load", payload: lists });
                setStatus("idle")
            } else {
                setStatus("error")
            }
        }

        fetchData();

    }, [request, response, dispatch, serviceId, user?.hasEmail, user?.hasPhone]);

    return { status };
};
