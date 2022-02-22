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

    // use this data to help fill for now
    const inputData = {
        name: "some name"
        , language: "en"
        , service_id: "a7902fc7-37f0-419c-84c8-3ab499ee24c8"
        , subscribe_email_template_id: "4c19c576-3cb0-452f-a573-fb6b126b680f"
        , subscribe_redirect_url: "https://articles.cdssandbox.xyz"
    }

    return data.id ? <Navigate to="/" replace={true} /> : <ListForm formData={ inputData } handler={onSubmit} />
}