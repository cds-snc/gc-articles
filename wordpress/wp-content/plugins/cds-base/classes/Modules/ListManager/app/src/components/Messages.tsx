import * as React from 'react';
import Swal from "sweetalert2";

import { useList } from "../store/ListContext";

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
    const { state } = useList();

    if (state.messages && state.messages.length >= 1) {

        state.messages.map((item: Message) => {
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