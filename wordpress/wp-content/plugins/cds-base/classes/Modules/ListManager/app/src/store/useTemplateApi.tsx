import localForage from "localforage";
import { useParams } from "react-router-dom";
import { v4 as uuidv4 } from 'uuid';
import { useCallback } from 'react';
import { Descendant } from "slate";

import { serialize, deserialize } from "../messages/editor/utils";
import { TemplateType } from "../types";

const connect = () => {
    const db = localForage.createInstance({ name: "gclists" });
    return db;
}

function useTemplateApi() {
    const params = useParams();
    const storage = connect();
    const templateId = params?.templateId;

    const getTemplate = useCallback(async (templateId: string) => {
        const template: TemplateType | null = await storage.getItem(templateId);
        const parsedContent = deserialize(template?.content || "");
        return { ...template, parsedContent };
    }, [storage])

    const getTemplates = useCallback(async () => {
        let templates: any = [];
        await storage.iterate((value: {}, key) => {
            templates.push({ templateId: key, type: "email", ...value });
        });
        return templates;
    }, [storage])

    const saveTemplate = useCallback(async ({ templateId, name, subject, content }: { templateId: string | undefined, name: string, subject: string, content: Descendant[] | undefined }) => {
        if (!content) return;

        const tId = templateId ? templateId : uuidv4();
        await storage.setItem(tId, {
            name,
            subject,
            content: serialize(content),
            timestamp: new Date().getTime()
        });
    },
        [storage],
    );

    const deleteTemplate = useCallback(async ({ templateId }: { templateId: string | undefined }) => {
        if (!templateId) return;
        await storage.removeItem(templateId);
    },
        [storage],
    );

    return { templateId, getTemplate, getTemplates, saveTemplate, deleteTemplate }
}

export default useTemplateApi;