/**
 * External dependencies
 */
import { useParams } from "react-router-dom";
import { useCallback, useState } from 'react';
import { Descendant } from "slate";
import useFetch, { CachePolicies } from 'use-http';

/**
 * Internal dependencies
 */
import { serialize, deserialize } from "../messages/editor/utils";
import { TemplateType } from "../types";

export function useTemplateApi() {
    const params = useParams();
    const templateId = params?.templateId;
    const { request, response } = useFetch({ data: [], cachePolicy: CachePolicies.NO_CACHE });
    const [templates, setTemplates] = useState([]);
    // @ts-ignore
    const [template, setTemplate] = useState<TemplateType>({ name: "", subject: "", body: "", parsedContent: deserialize("") });
    const [loading, setLoading] = useState(false);
    const [loadingTemplate, setLoadingTemplate] = useState(false);

    const getTemplate = useCallback(async (templateId: string | undefined) => {
        if (!templateId || templateId === 'new') return;

        setLoadingTemplate(true);
        await request.get(`/messages/${templateId}`)

        if (response.ok) {
            const template: TemplateType | null = response.data;

            if (!template || !template.body) {
                setTemplate({ name: "", subject: "", body: "" })
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
        setLoading(true);
        let templates: any = [];
        await request.get("/messages?sort=desc");

        if (response.ok) {
            response.data.forEach((item: any) => {
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
        if (!content) return;

        if (templateId === 'new') {
            await request.post('/messages', {
                'name': name,
                'subject': subject,
                'body': serialize(content),
                'message_type': 'email'
            });

            return response.data;
        }

        await request.put(`/messages/${templateId}`, {
            'name': name,
            'subject': subject,
            'body': serialize(content),
            'message_type': 'email'
        });

        if (response.ok) {
            return response.data;
        }

        return false;
    },
        [request, response],
    );

    const deleteTemplate = useCallback(async ({ templateId }: { templateId: string | undefined }) => {
        if (!templateId) return;

        await request.delete(`/messages/${templateId}`);

        if (response.ok) {
            return response.data;
        }

        return false;

        // @TODO: refresh table
    },
        [request, response],
    );

    const recordSent = useCallback(async (templateId: string | undefined, listId: string | undefined, listName: string | undefined, name: string | undefined, subject: string | undefined, body: string | undefined) => {

        const endpoint = templateId === 'new' ? '/messages/send' : `/messages/${templateId}/send`;
        await request.post(endpoint, {
            'sent_to_list_id': listId,
            'sent_to_list_name': listName,
            'name': name,
            'subject': subject,
            'body': body,
            'message_type': 'email',
        });

        if (response.ok) {
            return response.data;
        }
        return false;
    }, [request, response])

    return { template, loadingTemplate, templates, loading, templateId, getTemplate, getTemplates, saveTemplate, deleteTemplate, recordSent }
}

export default useTemplateApi;
