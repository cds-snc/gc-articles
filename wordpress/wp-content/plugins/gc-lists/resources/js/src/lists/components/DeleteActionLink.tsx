/**
 * External dependencies
 */
import * as React from 'react';
import useFetch from "use-http";

/**
 * Internal dependencies
 */
import { ConfirmActionLink } from "./ConfirmActionLink";
import { useList } from "../../store";

export const DeleteActionLink = ({ id = '' }: { id: string }) => {
    const { dispatch, state: { config: { listManagerApiPrefix } } } = useList();
    const { request, response } = useFetch(listManagerApiPrefix, { data: [] })

    const deleteList = async ({ id = '' }: { id: string }) => {
        await request.delete(`/list/${id}`);

        if (response.ok) {
            dispatch({ type: "delete", payload: { id } });
        }
    }

    return <ConfirmActionLink text={"Delete"} isConfirmedHandler={() => deleteList({ id })} />
}
