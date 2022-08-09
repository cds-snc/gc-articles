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
import { ErrorResponse, ServerErrors, FieldError, List, ListId } from "../../types";
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

    const [responseData, setResponseData] = useState<ListId>({ id: null });
    const [errors, setErrors] = useState<ServerErrors>([]);
    const { state: { lists } } = useList();
    const { listId } = useService()
    useListFetch();

    const onSubmit: SubmitHandler<List> = data => updateList(listId, data);

    const updateList = useCallback(async (listId: string | undefined, formData: List) => {
    // remove extra fields from payload
    const { id, subscriber_count, active, ...updateData } = formData;

    await request.put(`list/${listId}`, updateData)

    if (response.ok) {
        cache.clear();
        setResponseData({ id: id });
        return;
    }

    setErrors(await parseError(response));
    return;

    }, [response, request, cache]);

    const list = lists.filter((list: any) => {
        return list.id === listId
    })[0];

    if (responseData.id) {
        return <Navigate to={`/lists`} replace={true} />
    }

    return list ? (
        <>
            <StyledLink to={`/lists`}>
                <Back /> <span>{__("Back to mailing lists", "gc-lists")}</span>
            </StyledLink>
            <h1>{__("Edit list", "gc-lists")}</h1>
            <ListForm formData={list} handler={onSubmit} serverErrors={errors} />
        </>)
         : null
}

export default UpdateList;
