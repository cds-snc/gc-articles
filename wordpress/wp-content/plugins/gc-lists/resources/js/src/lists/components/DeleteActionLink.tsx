import * as React from 'react';
import useFetch from "use-http";
import { ConfirmActionLink } from "./ConfirmActionLink";
import { useList } from "../../store/ListContext";


export const DeleteActionLink = ({ id = '' }: { id: string }) => {
    const REST_URL = window?.CDS_VARS?.rest_url;
    const { request, response } = useFetch(`${REST_URL}list-manager`, { data: [] })
    const { dispatch } = useList();

    const deleteList = async ({ id = '' }: { id: string }) => {

        if (process.env.NODE_ENV !== "development") {
            await request.delete(`/${id}`);

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
