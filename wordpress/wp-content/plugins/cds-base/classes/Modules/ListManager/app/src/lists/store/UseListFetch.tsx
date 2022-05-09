import { useEffect, useState } from 'react';
import useFetch from 'use-http';
import { useList } from "../store/ListContext";
import { sendListData } from "./SaveListData"
import { getListType } from "../util";
import { List, ListType } from '../types';
import { useService } from '../util/useService';

export const useListFetch = () => {
    const { dispatch, state: { user } } = useList();
    const { serviceId } = useService();
    const [status, setStatus] = useState('idle');
    const { request, response } = useFetch({ data: [] })

    useEffect(() => {
        const fetchData = async () => {
            setStatus("loading")

            if (process.env.NODE_ENV === "development") {
                await request.get("list.json");
            } else {
                await request.get(`/lists/${serviceId}`);
            }

            if (response.ok) {
                let lists = await response.json();
                lists = lists.filter((list: List) => {
                    const listType = getListType(list.language)
                    if (listType === ListType.EMAIL && user?.hasEmail) {
                        return true
                    }
                    if (listType === ListType.PHONE && user?.hasPhone) {
                        return true
                    }
                    return false
                })

                dispatch({ type: "load", payload: lists })
                setStatus("idle")
            } else {
                setStatus("error")
            }

            if (process.env.NODE_ENV === "development") {
                return;
            }

            // sync list from List Manager API to local WP Option
            try {
                const listData = await response.json();
                const lists = listData?.map((list: any) => {
                    //@todo --- temporary... note we're using language here -> en=email, fr=phone 
                    return { id: list?.id, label: list?.name, type: getListType(list?.language) }
                })
                const REST_URL = window?.CDS_VARS?.rest_url;
                const REST_NONCE = window?.CDS_VARS?.rest_nonce;
                await sendListData(`${REST_URL}list-manager-settings/list/save`, REST_NONCE, { "list_values": lists });
            } catch (e) {
                console.log(e)
            }
        }

        fetchData();

    }, [request, response, dispatch, serviceId, user?.hasEmail, user?.hasPhone]);

    return { status };
};