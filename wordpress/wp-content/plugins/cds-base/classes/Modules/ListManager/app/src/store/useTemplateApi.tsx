import { useParams } from "react-router-dom";
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

        if (response.ok) {
            const result = await response.json();
            const template: TemplateType | null = result;

            if (!template || !template.body) {
                return { name: "", subject: "", body: "" }
            }


            let parsedContent;

            try {
                parsedContent = deserialize(template?.body || "");
                return { ...template, parsedContent };
            } catch (e) {
                //console.log(e);
                return { name: "", subject: "", body: "" }
            }

        }
    }, [request, response])

    const getTemplates = useCallback(async () => {
        let templates: any = [];

        await request.get("/messages");

        console.log("getTemplates", await response.json());

        if (response.ok) {
            const result = await response.json()
            result.forEach((item: any) => {
                templates.push({
                    templateId: item.id,
                    type: item.message_type,
                    ...item
                })
            });
        }
        return templates;
    }, [request, response])

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

    return { templateId, getTemplate, getTemplates, saveTemplate, deleteTemplate }
}

export default useTemplateApi;