import * as React from 'react';
import useFetch from "use-http";
import { ConfirmActionLink } from "./ConfirmActionLink";
import { useList } from "../../store/ListContext";

export const ResetActionLink = ({ id = '' }: { id: string }) => {
    const REST_URL = window?.CDS_VARS?.rest_url;
    const { request, response } = useFetch(`${REST_URL}list-manager`, { data: [] })
    const { dispatch } = useList();

    const resetList = async ({ id = '' }: { id: string }) => {

        if (process.env.NODE_ENV !== "development") {
            await request.put(`/${id}/reset`)

            if (response.ok) {
                dispatch({ type: "reset", payload: { id } });
            }
        } else {
            // for local dev --- dispatch the reset
            dispatch({ type: "reset", payload: { id } });
        }
    }

    return <ConfirmActionLink text={"Reset"} isConfirmedHandler={() => resetList({ id })} />
}
