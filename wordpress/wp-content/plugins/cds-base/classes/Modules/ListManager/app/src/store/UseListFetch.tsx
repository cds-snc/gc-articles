import { useEffect, useState } from 'react';
import useFetch from 'use-http';
import { useParams } from "react-router-dom";
import { useList } from "../store/ListContext";
import { sendListData } from "./SaveListData"

export const useListFetch = () => {
    const { dispatch } = useList();
    const params = useParams();
    const serviceId = params?.serviceId;

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
                dispatch({ type: "load", payload: await response.json() })
                setStatus("idle")
            } else {
                setStatus("error")
            }

            if (process.env.NODE_ENV === "development") {
                return;
            }

            // sync list from List Manager API to local WP Option
            const listData = await response.json();
            const lists = listData.map((list: any) => {
                return { id: list?.id, label: list?.name, type: "email" }
            })
            const REST_URL = window?.CDS_VARS?.rest_url;
            const REST_NONCE = window?.CDS_VARS?.rest_nonce;
            await sendListData(`${REST_URL}list-manager-settings/list/save`, REST_NONCE, { "list_values": lists });
        }

        fetchData();

    }, [request, response, dispatch, serviceId]);

    return { status };
};