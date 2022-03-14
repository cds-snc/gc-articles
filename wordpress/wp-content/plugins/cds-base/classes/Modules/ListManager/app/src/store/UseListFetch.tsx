import { useEffect, useState } from 'react';
import useFetch from 'use-http';
import { useParams } from "react-router-dom";
import { useList } from "../store/ListContext";


export const useListFetch = () => {
    const { dispatch } = useList();
    const params = useParams();
    const serviceId = params?.serviceId;

    const [status, setStatus] = useState('idle');
    const { request, response } = useFetch({ data: [] })

    useEffect(() => {
        const fetchData = async () => {
            setStatus("loading")
            await request.get(`/lists/${serviceId}`);

            if (response.ok) {
                dispatch({ type: "load", payload: await response.json() })
                setStatus("idle")
            } else {
                setStatus("error")
            }
        }

        fetchData();

    }, [request, response, dispatch, serviceId]);

    return { status };
};