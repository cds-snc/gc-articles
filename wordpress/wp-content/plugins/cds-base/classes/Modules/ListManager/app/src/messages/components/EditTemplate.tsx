import * as React from 'react';
import { useEffect, useState } from 'react';
import { Descendant } from "slate";

import { Editor } from "../editor/Editor";
import useTemplateApi from '../../store/useTemplateApi';
import { deserialize } from "../editor/utils";

export const EditTemplate = () => {
    const [currentTemplate, setCurrentTemplate] = useState<Descendant[]>();
    const { templateId, getTemplate, saveTemplate } = useTemplateApi();
    useEffect(() => {
        const loadTemplate = async () => {
            if (templateId) {
                const template = await getTemplate(templateId);
                setCurrentTemplate(deserialize(template.content || ""));
            }
        }
        loadTemplate();
    }, [])
    return (
        <>
            <div>
                <strong>Message name</strong>
                <p>Your recipients will not see this message name.</p>
                <input type="text" id="name" name="name"></input>
            </div>

            <div>
                <strong>Subject line of the email</strong>
                <p>Tell recipients what the message is about. Try to keep it shorter than 10 words.</p>
                <input type="text" id="subject" name="subject"></input>
            </div>
            {currentTemplate && <Editor template={currentTemplate} handleChange={setCurrentTemplate} />}
            <button className="button" onClick={() => { }}>Send message to list</button>
            <button className="button" onClick={() => {
                saveTemplate({ templateId, name: "name", subject: "title", content: currentTemplate })
            }}>Save template</button>
            {/* <a href="#">Delete this message template</a> */}
        </>
    )
}

export default EditTemplate;