import useFetch from "use-http";

import { useList } from "../store/ListContext";
import { ConfirmActionLink } from "./ConfirmActionLink"


export const DeleteActionLink = ({id = ''}:{id: string}) => {
    const { request, response } = useFetch({ data: [] })
    const { dispatch } = useList();

    const deleteList = async ({ id = '' }: { id: string }) => {
        await request.delete(`/list/${id}`)

        if (response.ok) {
            dispatch({ type: "delete", payload: { id } });
        }
    }

    return <ConfirmActionLink text={"delete"} isConfirmedHandler={() => deleteList({ id })} />
}