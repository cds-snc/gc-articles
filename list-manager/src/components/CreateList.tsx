import { useState, useCallback } from 'react'
import { SubmitHandler } from "react-hook-form";
import useFetch from 'use-http';
import { Navigate } from "react-router-dom";

import { List } from "../types";
import { useList } from "../store/ListContext";
import { ListForm } from "./ListForm";

export const CreateList = () => {
    const { request, cache, response } = useFetch({ data: [] })
    const [data, setData] = useState({ id: null })
    const { dispatch } = useList();

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

    // use this data to help fill for now
    const inputData = {
        name: "some name"
        , language: "en"
        , service_id: "a7902fc7-37f0-419c-84c8-3ab499ee24c8"
        , subscribe_email_template_id: "4c19c576-3cb0-452f-a573-fb6b126b680f"
        , subscribe_redirect_url: "https://articles.cdssandbox.xyz"
    }

    return data.id ? <Navigate to="/" replace={true} /> : <ListForm formData={inputData} handler={onSubmit} />
}

export default CreateList;