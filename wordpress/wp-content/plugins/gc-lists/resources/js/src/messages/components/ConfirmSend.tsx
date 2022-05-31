import { __, sprintf } from "@wordpress/i18n";
import Swal from "sweetalert2";

export const ConfirmSend = async ({ count }: { count: number }) => {
    let result = await Swal.fire({
        title: __("Send to list?", "cds-snc"),
        text: sprintf("This list has %s subscribers. It’s not possible to cancel a message after sending.", count, "cds-snc"),
        imageUrl: process.env.PUBLIC_URL + "/warn.png",
        imageWidth: 68,
        imageHeight: 68,
        imageAlt: __("warning", "cds-snc"),
        confirmButtonText: __("Yes send it", "cds-snc"),
        cancelButtonText: __("No, cancel", "cds-snc"),
        showCancelButton: true
    });

    if (result.isConfirmed) {
        return true;
    }

    return false;
}