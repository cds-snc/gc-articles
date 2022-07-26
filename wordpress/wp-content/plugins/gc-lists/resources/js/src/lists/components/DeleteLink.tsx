/**
 * External dependencies
 */
import useFetch from "use-http";
import styled from 'styled-components';
import Swal from "sweetalert2";
import { __ } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import { useList } from "../../store";

const StyledDeleteLink = styled.button`
    color: #D3080C;
    background: none;
    border: none;
    cursor: pointer;
    text-decoration:underline;
    :hover{
        text-decoration:none;
    }
`

const ConfirmDialog = async () => {
    let result = await Swal.fire({
        title: __('Warning!', "gc-lists"),
        text: __('This is a destructive action. Are you sure you want to continue?', "gc-lists"),
        icon: 'error',
        confirmButtonText: __('Yes I\'m sure', "gc-lists"),
        showCancelButton: true
    });

    if (result.isConfirmed) {
        return true;
    }

    return false;
}

export const DeleteLink = ({ listId = '' }: { listId: string }) => {
    const { dispatch, state: { config: { listManagerApiPrefix } } } = useList();
    const { request, response } = useFetch(listManagerApiPrefix, { data: [] })

    const deleteList = async ({ listId = '' }: { listId: string }) => {
        await request.delete(`/list/${listId}`);

        if (response.ok) {
            dispatch({ type: "delete", payload: { id: listId } });
        }
    }

    return (
        <StyledDeleteLink className="action" onClick={async (e) => {
            e.preventDefault();
            let result = await ConfirmDialog();

            if (result) {
                await deleteList({ listId })
            }
        }}>
            {__("Delete", "gc-lists")}
        </StyledDeleteLink>
    )
}
