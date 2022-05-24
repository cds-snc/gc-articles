import localForage from "localforage";
import { useParams } from "react-router-dom";
import { v4 as uuidv4 } from 'uuid';
import { useCallback } from 'react';
import { Descendant } from "slate";

import { serialize, deserialize } from "../messages/editor/utils";
import { TemplateType } from "../types";
import useFetch from 'use-http';

function useTemplateApi() {
    const params = useParams();
    const templateId = params?.templateId;
    const { request, response } = useFetch({ data: [] })

    const getTemplate = useCallback(async (templateId: string) => {
        await request.get(`/messages/${templateId}`)
        const result = await response.json();
        const template: TemplateType | null = result;
        // const template: TemplateType | null = await storage.getItem(templateId);
        const parsedContent = deserialize(template?.body || "");
        return { ...template, parsedContent };
    }, [])

    const getTemplates = useCallback(async () => {
        let templates: any = [];

        await request.get("/messages");

        if (response.ok) {
            const result = await response.json()
            result.forEach((item: any) => {
                templates.push({
                    templateId: item.id,
                    type: item.message_type,
                    ...item
                })
            })
        }
        return templates;
    }, [])

    const saveTemplate = useCallback(async ({ templateId, name, subject, content }: { templateId: string | undefined, name: string, subject: string, content: Descendant[] | undefined }) => {
        if (!content) return;

        if (templateId === 'new') {
            await request.post('/messages', {
                'name': name,
                'subject': subject,
                'body': serialize(content),
                'message_type': 'email'
            });

            const result = await response.json();
            console.log("created", result);

            return result;
        }

        await request.put(`/messages/${templateId}`, {
            'name': name,
            'subject': subject,
            'body': serialize(content),
            'message_type': 'email'
        });

        const result = await response.json();

        console.log("updated", result)

        // @TODO: redirect back
        return result;
    },
        [],
    );

    const deleteTemplate = useCallback(async ({ templateId }: { templateId: string | undefined }) => {
        if (!templateId) return;

        await request.delete(`/messages/${templateId}`);

        const result = await response.json();

        // @TODO: refresh table
    },
        [],
    );

    return { templateId, getTemplate, getTemplates, saveTemplate, deleteTemplate }
}

export default useTemplateApi;