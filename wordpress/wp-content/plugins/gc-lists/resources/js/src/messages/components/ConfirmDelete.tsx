/**
 * External dependencies
 */
import { __ } from "@wordpress/i18n";
import Swal from "sweetalert2";

export const ConfirmDelete = async () => {
    let result = await Swal.fire({
        title: __("Delete draft?", "gc-lists"),
        text: __("This will permanently discard the message draft.", "gc-lists"),
        imageUrl: process.env.PUBLIC_URL + "/warn.png",
        imageWidth: 68,
        imageHeight: 68,
        imageAlt: __("warning", "gc-lists"),
        confirmButtonText: __("Yes delete it", "gc-lists"),
        cancelButtonText: __("No, keep it", "gc-lists"),
        showCancelButton: true
    });

    if (result.isConfirmed) {
        return true;
    }

    return false;
}
