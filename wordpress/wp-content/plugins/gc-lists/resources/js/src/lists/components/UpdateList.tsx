/**
 * External dependencies
 */
import { useState, useCallback } from 'react'
import useFetch from 'use-http';
import { SubmitHandler } from "react-hook-form";
import { Navigate } from "react-router-dom";
import { __ } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import { ListForm } from "./ListForm";
import { useService, useList, useListFetch } from '../../store';
import { ErrorResponse, ServerErrors, FieldError, List, ListType, ListIdAndType } from "../../types";
import { Back, StyledLink } from "../../common";

const parseError = async (response: Response) => {
    try {
        const err: ErrorResponse = await response.json();
        return err.detail.map((item): FieldError => {
            return { name: item.loc[1], msg: item.msg };
        })
    } catch (e) {
        console.log((e as Error).message)
        return []
    }
}

export const UpdateList = () => {
    const { state: { config: { listManagerApiPrefix } } } = useList();
    const { request, cache, response } = useFetch(listManagerApiPrefix, { data: [] })

    const [responseData, setResponseData] = useState<ListIdAndType>({ id: null, type: ListType.EMAIL });
    const [errors, setErrors] = useState<ServerErrors>([]);
    const { state: { lists } } = useList();
    const { listId } = useService()
    useListFetch();

    const onSubmit: SubmitHandler<List> = data => updateList(listId, data);

    const updateList = useCallback(async (listId: string | undefined, formData: List) => {
        // remove extra fields from payload
        const { id, subscriber_count, active, language, ...updateData } = formData;

        await request.put(`list/${listId}`, updateData)

        if (response.ok) {
            cache.clear();
            const listType = language === 'en' ? ListType.EMAIL : ListType.PHONE
            setResponseData({ id: id, type: listType });
            return;
    }

    setErrors(await parseError(response));
    return;

    }, [response, request, cache]);

    const list = lists.filter((list: any) => {
        return list.id === listId
    })[0];

    if (responseData.id) {
        // @ TODO: don't do this for editing lists
        return <Navigate to={`/lists/${responseData.id}/choose-subscribers/${responseData.type}`} replace={true} />
    }

    let subscriberMessage = '';
    if(list && list.subscriber_count !== undefined) {
        const subscriber_count = parseInt(list.subscriber_count)
        subscriberMessage = subscriber_count > 1 ?
            __(`This list has ${subscriber_count} subscribers.`, "gc-lists") :
            subscriber_count === 1 ?
            __("This list has 1 subscriber.", "gc-lists") :
            __("This list has no subscribers.", "gc-lists");
    }

    return list ? (
        <>
            <StyledLink to={`/lists`}>
                <Back /> <span>{__("Back to mailing lists", "gc-lists")}</span>
            </StyledLink>
            <h1>{__("Edit list", "gc-lists")}</h1>
            {subscriberMessage && <p>{subscriberMessage}</p>}
            <ListForm formData={list} handler={onSubmit} serverErrors={errors} />
        </>)
         : null
}

export default UpdateList;
