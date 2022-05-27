import { useParams } from "react-router-dom";
import { useCallback, useState } from 'react';
import { Descendant } from "slate";
import { v4 as uuidv4 } from "uuid";
import useFetch from 'use-http';

import { serialize, deserialize } from "../messages/editor/utils";
import { TemplateType } from "../types";

function useTemplateApi() {
    const params = useParams();
    const templateId = params?.templateId;
    const { request, response } = useFetch({ data: [] });
    const [templates, setTemplates] = useState([]);
    // @ts-ignore
    const [template, setTemplate] = useState({ name: "", subject: "", body: "", parsedContent: deserialize("") });
    const [loading, setLoading] = useState(false);
    const [loadingTemplate, setLoadingTemplate] = useState(false);

    const getTemplate = useCallback(async (templateId: string | undefined) => {
        console.log("getTemplate");
        if (!templateId || templateId === 'new') return;

        setLoadingTemplate(true);
        await request.get(`/messages/${templateId}?c=${uuidv4()}`)

        if (response.ok) {
            const result = await response.json();
            const template: TemplateType | null = result;

            if (!template || !template.body) {
                setTemplate({ name: "", subject: "", body: "", parsedContent: deserialize("") })
            }

            let parsedContent;

            try {
                parsedContent = deserialize(template?.body || "");
                // @ts-ignore
                setTemplate({ ...template, parsedContent })
            } catch (e) {
                //console.log(e);
                return { name: "", subject: "", body: "" }
            }

            setLoadingTemplate(false);

        }
    }, [request, response])

    const getTemplates = async () => {
        console.log("getTemplates");
        setLoading(true);
        let templates: any = [];
        await request.get(`/messages?c=${uuidv4()}`);

        if (response.ok) {
            const result = await response.json()
            result.forEach((item: any) => {
                templates.push({
                    templateId: item.id,
                    type: item.message_type,
                    ...item
                })
            });

            setTemplates(templates);
            setLoading(false);
        }
    };

    const saveTemplate = useCallback(async ({ templateId, name, subject, content }: { templateId: string | undefined, name: string, subject: string, content: Descendant[] | undefined }) => {
        console.log("saveTemplate", templateId, subject)
        if (!content) return;

        if (templateId === 'new') {
            await request.post('/messages', {
                'name': name,
                'subject': subject,
                'body': serialize(content),
                'message_type': 'email'
            });

            const result = await response.json();
            return result;
        }

        await request.put(`/messages/${templateId}`, {
            'name': name,
            'subject': subject,
            'body': serialize(content),
            'message_type': 'email'
        });

        if (response.ok) {
            const result = await response.json();
            return result;
        }

        return false;
    },
        [request, response],
    );

    const deleteTemplate = useCallback(async ({ templateId }: { templateId: string | undefined }) => {
        if (!templateId) return;

        await request.delete(`/messages/${templateId}`);

        console.log(response);

        if (response.ok) {
            return await response.json();
        }

        return false;

        // @TODO: refresh table
    },
        [request, response],
    );

    const recordSent = useCallback(async (templateId: string | undefined, listId: string | undefined, listName: string | undefined, name: string | undefined, subject: string | undefined, body: string | undefined) => {

        const endpoint = templateId == 'new' ? '/messages/send' : `/messages/${templateId}/send`;

        await request.post(endpoint, {
            'sent_to_list_id': listId,
            'sent_to_list_name': listName,
            'name': name,
            'subject': subject,
            'body': body,
            'message_type': 'email',
        });

        if (response.ok) {
            await response.json();
            return true;
        }
        return false;
    }, [request, response])

    return { template, loadingTemplate, templates, loading, templateId, getTemplate, getTemplates, saveTemplate, deleteTemplate, recordSent }
}

export default useTemplateApi;