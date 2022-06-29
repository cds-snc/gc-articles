/**
 * External dependencies
 */
import * as React from 'react';
import { __ } from "@wordpress/i18n";
import Swal from "sweetalert2";

export const ConfirmDelete = async () => {
    let result = await Swal.fire({
        title: __("Are you sure?", "gc-lists"),
        text: __("Are you sure you want to delete this message?", "gc-lists"),
        imageUrl: process.env.PUBLIC_URL + "/warn.png",
        imageWidth: 68,
        imageHeight: 68,
        imageAlt: __("warning", "gc-lists"),
        confirmButtonText: __("Yes delete it", "gc-lists"),
        cancelButtonText: __("No, cancel", "gc-lists"),
        showCancelButton: true
    });

    if (result.isConfirmed) {
        return true;
    }

    return false;
}
