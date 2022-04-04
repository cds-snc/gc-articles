import * as React from 'react';
import useFetch from "use-http";
import { useList } from "../store/ListContext";
import { ConfirmActionLink } from "./ConfirmActionLink"

export const ResetActionLink = ({ id = '' }: { id: string }) => {
    const { request, response } = useFetch({ data: [] })
    const { dispatch } = useList();

    const resetList = async ({ id = '' }: { id: string }) => {

        if (process.env.NODE_ENV !== "development") {
            await request.put(`/list/${id}/reset`)

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