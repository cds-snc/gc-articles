/**
 * External dependencies
 */
import { useEffect, useRef } from "react";
import Swal from "sweetalert2";

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


export const ToastMessage = (state: any) => {
    const { state: { messages } } = state;
    const message = useRef();

    useEffect(() => {
        if (message.current) {
            return;
        }

        if (messages && messages.length >= 1) {
            messages.forEach((item: Message) => {
                if (item.type === "add" || item.type === "saved" || item.type === "delete" || item.type === "reset") {
                    Toast.fire({
                        icon: "success",
                        title: item.message
                    });
                    // @ts-ignore
                    message.current = item.id;
                }
            })
        }
    }, [state, messages])

    return null;
}