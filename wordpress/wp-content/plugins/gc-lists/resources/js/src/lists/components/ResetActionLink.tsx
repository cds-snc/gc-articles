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

export const ResetActionLink = ({ id = '' }: { id: string }) => {
    const { dispatch, state: { config: { listManagerApiPrefix } } } = useList();
    const { request, response } = useFetch(listManagerApiPrefix, { data: [] })

    const resetList = async ({ id = '' }: { id: string }) => {
        await request.put(`/list/${id}/reset`)

        if (response.ok) {
            dispatch({ type: "reset", payload: { id } });
        }
    }

    return <ConfirmActionLink text={"Reset"} isConfirmedHandler={() => resetList({ id })} />
}
