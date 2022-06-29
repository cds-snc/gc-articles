/**
 * External dependencies
 */
import * as React from 'react';
import Swal from "sweetalert2";

/**
 * Internal dependencies
 */
import { useList } from "../../store";

type Message = {
    id: string,
    type: string,
    message: string;
};

const Toast = Swal.mixin({
    toast: true,
    position: 'top-right',
    iconColor: 'white',
    customClass: {
        popup: 'colored-toast'
    },
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true
})


export const Messages = () => {
    const { state: { messages } } = useList();

    if (messages && messages.length >= 1) {

        messages.map((item: Message) => {
            if (item.type === "add" || item.type === "delete" || item.type === "reset") {

                Toast.fire({
                    icon: "success",
                    title: 'Success'
                })

                // @todo  dispatch to clear messages

            }
            return true
        })

    }

    return null;
}