// @ts-nocheck
import { useCallback, useState } from "react";
import { useLocation } from "react-router-dom";
import useFetch from 'use-http';
import { useList } from './ListContext';

function useSendTemplate({ listId, content }) {
    const REST_URL = window?.CDS_VARS?.rest_url;
    const { request, response } = useFetch(`${REST_URL}list-manager`, { data: [] })
    const [errors, setErrors] = useState(false);
    const [success, setSuccess] = useState(false);
    const { state } = useList();

    const { state: { subject, name, template: editorTemplate } } = useLocation();

    console.log(name, subject, editorTemplate)



    const send = useCallback(async (data: string) => {
        let endpoint = "/send"
        let post_data = {
            list_id: listId,
            template_id: state.serviceData[0].sendingTemplate,
            template_type: 'email',
            job_name: 'job',
            personalisation: JSON.stringify({ message: content, subject: 'Huzzah!' }),
        }

        await request.post(endpoint, post_data);

        if (response && response.status !== 200) {
            setErrors(true);
            return false;
        }

        if (response && response.status === 200) {
            setSuccess(true);
            return true;
        }

    }, [response, request, listId, content, state]);

    // send the template
    const sendTemplate = useCallback(
        async () => {
            if (!content) return;
            return await send(content);
        },
        [content, send],
    );

    const reset = useCallback(
        () => {
            setErrors(false);
            setSuccess(false);
        },
        [setErrors, setSuccess],
    );

    return { sendTemplate, errors, success, reset };
}

export default useSendTemplate;