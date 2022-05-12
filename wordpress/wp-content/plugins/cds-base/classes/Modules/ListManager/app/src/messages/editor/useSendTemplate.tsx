// @ts-nocheck
import { useCallback } from "react";
import useFetch from 'use-http';

function useSendTemplate({ listId, content }) {
    const { request, response } = useFetch({ data: [] });


    const send = useCallback(async (data: string) => {
        let endpoint = "/send"
        let post_data = {
            list_id: listId,
            template_id: '40454604-8702-4eeb-9b38-1ed3104fb960', // @todo this will come form WP
            template_type: 'email',
            job_name: 'job',
            personalisation: JSON.stringify({ message: content, subject: 'Huzzah!' }),
        }

        await request.post(endpoint, post_data)

        console.log(response)
    }, [response, request, listId, content]);

    // send the template
    const sendTemplate = useCallback(
        () => {
            if (!content) return;
            send(content);
        },
        [content, send],
    );

    return sendTemplate;
}

export default useSendTemplate;