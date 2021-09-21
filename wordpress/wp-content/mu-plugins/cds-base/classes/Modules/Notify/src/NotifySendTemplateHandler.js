import { useEffect } from 'react';
import { confirmSend } from 'Notify/util.js';
import { findListById } from "./NotifyPanel"
import { __ } from "@wordpress/i18n";

const formId = "notify_template_sender_form";
const listIds = "list_id";

const handleSubmit = async ({ selectedOption, listCounts }) => {
    try {

        const list = findListById(listCounts, selectedOption.split("~")[0]);

        let text = '';
        text = __("This list has ", "cds-snc") + list.subscriber_count + __(" subscribers.  ", "cds-snc");
        text += __("You won't be able to revert this", "cds-snc");


        let confirmed = await confirmSend(text);

        if (confirmed) {
            document.forms[formId].submit();
        }

    } catch (e) {
        console.log(e);
    }

}

export const NotifySendTemplateHandler = ({ listCounts }) => {

    useEffect(() => {

        const el = document.getElementById(formId);

        if (!el) return

        el.addEventListener("submit", (e) => {
            e.preventDefault();
            const selectedOption = document.getElementById(listIds).selectedOptions[0].value;
            handleSubmit({ selectedOption, listCounts });
        });
    }, [])

    return null
}