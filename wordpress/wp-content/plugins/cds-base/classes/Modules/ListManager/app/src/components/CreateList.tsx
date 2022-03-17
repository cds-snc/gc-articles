import * as React from 'react';
import { useState, useCallback } from 'react'
import { SubmitHandler } from "react-hook-form";
import useFetch from 'use-http';
import { Navigate } from "react-router-dom";
import { List } from "../types";
import { useList } from "../store/ListContext";
import { ListForm } from "./ListForm";
import { useParams } from "react-router-dom";

export const CreateList = () => {
    const { request, cache, response } = useFetch({ data: [] })
    const [data, setData] = useState({ id: null })
    const { dispatch } = useList();
    const params = useParams();
    const serviceId = params?.serviceId;

    const createList = useCallback(async (formData: List) => {
        await request.post('/list', formData)

        if (response.ok) {
            cache.clear();
            const id = await response.json()
            setData(await response.json());
            dispatch({ type: "add", payload: id })
            return
        }
    }, [response, request, cache, dispatch]);

    const onSubmit: SubmitHandler<List> = data => createList(data);

    return data.id ? <Navigate to={`/service/${serviceId}`} replace={true} /> : <ListForm formData={{ service_id: serviceId }} serverErrors={[]} handler={onSubmit} />
}

export default CreateList;