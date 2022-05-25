// @ts-nocheck
import { useCallback, useState } from "react";
import useFetch from 'use-http';

function useSendTemplate({ listId, content }) {
    const REST_URL = window?.CDS_VARS?.rest_url;
    const { request, response } = useFetch(`${REST_URL}list-manager`, { data: [] })
    const [errors, setErrors] = useState(false);
    const [success, setSuccess] = useState(false);
    const send = useCallback(async (data: string) => {
        let endpoint = "/send"

        let post_data = {
            list_id: listId,
            template_id: '40454604-8702-4eeb-9b38-1ed3104fb960', // @todo this will come form WP
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

    }, [response, request, listId, content]);

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