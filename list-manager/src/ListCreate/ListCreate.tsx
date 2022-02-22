import React, { useState, useCallback } from 'react'
import { ListForm } from "../ListForm/ListForm";
import { SubmitHandler } from "react-hook-form";
import { Inputs } from "../types";
import useFetch from 'use-http';
import { Navigate } from "react-router-dom";

export const ListCreate = () => {

    const { post, response } = useFetch({ data: [] })
    const [data, setData] = useState({ id: null })

    const createList = useCallback(async (formData: Inputs) => {
        await post('/list', formData)

        if (response.ok) {
            setData(await response.json())
        }

        return {}


    }, [response, post]);

    const onSubmit: SubmitHandler<Inputs> = data => createList(data);

    return data.id ? <Navigate to="/" replace={true} /> : <ListForm handler={onSubmit} />
}