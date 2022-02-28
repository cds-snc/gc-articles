
import { useEffect, useState } from 'react';
import useFetch from 'use-http';
import { useList } from "../store/ListContext";

export const useListFetch = () => {
    const { dispatch } = useList();
    const [status, setStatus] = useState('idle');
    const { request, response } = useFetch({ data: [] })

    useEffect(() => {
        const fetchData = async () => {
            setStatus("loading")
            await request.get('/lists')
            if (response.ok) {
                dispatch({ type: "load", payload: await response.json() })
                setStatus("idle")
            } else {
                console.log("useListFetch", response)
            }
        }

        fetchData();

    }, [request, response, dispatch]);

    return { status };
};