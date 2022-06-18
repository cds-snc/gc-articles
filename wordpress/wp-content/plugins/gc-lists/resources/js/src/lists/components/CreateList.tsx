/**
 * External dependencies
 */
import * as React from 'react';
import { useState, useCallback } from 'react'
import { SubmitHandler } from "react-hook-form";
import useFetch from 'use-http';
import { Navigate } from "react-router-dom";

/**
 * Internal dependencies
 */
import { ListForm } from "./ListForm";
import { useList, useService } from "../../store";
import { List } from "../../types";

export const CreateList = () => {
    const [data, setData] = useState({ id: null })
    const { dispatch, state: { config: { listManagerApiPrefix } } } = useList();
    const { request, cache, response } = useFetch(listManagerApiPrefix, { data: [] })
    const { serviceId } = useService();

    const createList = useCallback(async (formData: List) => {
        await request.post('/list', formData)

        if (response.ok) {
            cache.clear();
            const id = response.data
            setData(id);
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
