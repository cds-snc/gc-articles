import * as React from 'react';
import useFetch from "use-http";

import { useList } from "../store/ListContext";
import { ConfirmActionLink } from "./ConfirmActionLink"


export const DeleteActionLink = ({ id = '' }: { id: string }) => {
    const { request, response } = useFetch({ data: [] })
    const { dispatch } = useList();

    const deleteList = async ({ id = '' }: { id: string }) => {

        if (process.env.NODE_ENV !== "development") {
            await request.delete(`/list/${id}`);

            if (response.ok) {
                dispatch({ type: "delete", payload: { id } });
            }
        } else {
            // for local dev --- dispatch the delete
            dispatch({ type: "delete", payload: { id } });
        }


    }

    return <ConfirmActionLink text={"Delete"} isConfirmedHandler={() => deleteList({ id })} />
}