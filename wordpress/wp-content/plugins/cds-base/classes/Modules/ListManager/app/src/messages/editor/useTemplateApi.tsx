import localForage from "localforage";
import { useParams } from "react-router-dom";
import { v4 as uuidv4 } from 'uuid';
import { useCallback } from 'react';
import { Descendant } from "slate";
import { serialize, deserialize } from "./utils";
import { TemplateType } from "../../types";

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

    const getTemplates = useCallback(() => {
        storage.iterate((value, key) => {
            console.log(value, key);
        })
    }, [storage])

    const saveTemplate = useCallback(
        (title: string, subject: string, value: Descendant[] | undefined) => {
            if (!value) return;
            storage.setItem(uuidv4(), {
                title,
                subject,
                content: serialize(value),
                timestamp: new Date().getTime()
            })
        },
        [storage],
    );

    return { templateId, getTemplate, getTemplates, saveTemplate }
}

export default useTemplateApi;