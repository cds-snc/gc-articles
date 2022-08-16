/**
 * External dependencies
 */
import * as React from 'react';
import { useState, useCallback } from 'react'
import { SubmitHandler } from "react-hook-form";
import useFetch from 'use-http';
import { Navigate } from "react-router-dom";
import { __ } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import { ListForm } from "./ListForm";
import { useList, useService } from "../../store";
import { List, ListType } from "../../types";
import { Back, StyledLink } from "../../common";

export const CreateList = () => {
    const [data, setData] = useState({ id: null, type: ListType.EMAIL })
    const { dispatch, state: { config: { listManagerApiPrefix } } } = useList();
    const { request, cache, response } = useFetch(listManagerApiPrefix, { data: [] })
    const { serviceId } = useService();

    const createList = useCallback(async (formData: List) => {
        await request.post('/list', formData)

        if (response.ok) {
            cache.clear();
            const id = response.data?.id
            const type = formData.language === 'en' ? ListType.EMAIL : ListType.PHONE
            setData({ id, type });
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


    return data.id ? <Navigate to={`/lists/${data.id}/choose-subscribers/${data.type}`} replace={true} /> : (
        <>
            <StyledLink to={`/lists`}>
                <Back /> <span>{__("Mailing lists", "gc-lists")}</span>
            </StyledLink>
            <h1>{__("Create new list", "gc-lists")}</h1>
            <ListForm formData={formData} serverErrors={[]} handler={onSubmit} />
        </>)
}

export default CreateList;
