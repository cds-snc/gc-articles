import { __, sprintf } from "@wordpress/i18n";
import Swal from "sweetalert2";

export const ConfirmSend = async ({ count }: { count: number }) => {
    let result = await Swal.fire({
        title: __("Send to list?", "gc-lists"),
        text: sprintf("This list has %s subscribers. It’s not possible to cancel a message after sending.", count, "gc-lists"),
        imageUrl: process.env.PUBLIC_URL + "/warn.png",
        imageWidth: 68,
        imageHeight: 68,
        imageAlt: __("warning", "gc-lists"),
        confirmButtonText: __("Yes send it", "gc-lists"),
        cancelButtonText: __("No, cancel", "gc-lists"),
        showCancelButton: true
    });

    if (result.isConfirmed) {
        return true;
    }

    return false;
}
