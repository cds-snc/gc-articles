import * as React from 'react';
import { useEffect, useState } from 'react';
import { Descendant } from "slate";
import { Link } from "react-router-dom";

import { Editor } from "../editor/Editor";
import useTemplateApi from '../../store/useTemplateApi';
import { deserialize } from "../editor/utils";
import { useService } from '../../util/useService';

export const EditTemplate = () => {
    const [currentTemplate, setCurrentTemplate] = useState<Descendant[]>();
    const { templateId, getTemplate, saveTemplate } = useTemplateApi();
    const { serviceId } = useService();
    useEffect(() => {
        const loadTemplate = async () => {
            if (templateId) {
                const template = await getTemplate(templateId);
                setCurrentTemplate(deserialize(template.content || ""));
            }
        }
        loadTemplate();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);
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

            <Link to={{ pathname: `/messages/${serviceId}/send/${templateId}` }}>Send Template</Link>

            <button className="button" onClick={() => {
                saveTemplate({ templateId, name: "name", subject: "title", content: currentTemplate })
            }}>Save template</button>
            {/* <a href="#">Delete this message template</a> */}
        </>
    )
}

export default EditTemplate;