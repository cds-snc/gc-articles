// @ts-nocheck
import React, { useCallback } from "react";
import useTemplateApi from "./useTemplateApi";
import { serialize } from "./utils";
import useFetch from 'use-http';

function useSendTemplate(listId) {
    const { request, response } = useFetch({ data: [] });
    const [template,] = useTemplateApi();

    const send = useCallback(async (data: string) => {
        let endpoint = "/send"
        let post_data = {
            list_id: listId,
            template_id: '40454604-8702-4eeb-9b38-1ed3104fb960', // @todo this will come form WP
            template_type: 'email',
            job_name: 'el-jobbo',
            personalisation: JSON.stringify({ message: data, subject: 'Huzzah!' }),
        }

        await request.post(endpoint, post_data)

        console.log(response)
    }, [response, request, listId]);

    // send the template
    const sendTemplate = useCallback(
        () => {
            if (!template) return;
            send(serialize(template))
        },
        [template, send],
    );

    return sendTemplate;
}

export default useSendTemplate;