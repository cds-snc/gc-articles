import * as React from 'react';
import { useState, useCallback } from 'react'
import { SubmitHandler } from "react-hook-form";
import useFetch from 'use-http';
import { Navigate } from "react-router-dom";
import { ListForm } from "./ListForm";
import { useList } from "../../store/ListContext";
import { List } from "../../types";
import { useService } from '../../util/useService';

export const CreateList = () => {
    const REST_URL = window?.CDS_VARS?.rest_url;
    const { request, cache, response } = useFetch(`${REST_URL}list-manager`,{ data: [] })
    const [data, setData] = useState({ id: null })
    const { dispatch } = useList();
    const { serviceId } = useService();

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
    const formData = {
        service_id: serviceId,
        language: "en",
        subscribe_redirect_url: "https://articles.alpha.canada.ca/thanks-for-subscribing-merci-pour-votre-labonnement",
        unsubscribe_redirect_url: "https://articles.alpha.canada.ca/unsubscribed-from-mailing-list-labonnement-supprime",
        confirm_redirect_url: "https://articles.alpha.canada.ca/confirmation"
    }



    return data.id ? <Navigate to={`/lists`} replace={true} /> : <ListForm formData={formData} serverErrors={[]} handler={onSubmit} />
}

export default CreateList;
