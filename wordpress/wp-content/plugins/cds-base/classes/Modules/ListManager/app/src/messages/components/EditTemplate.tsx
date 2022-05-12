import * as React from 'react';
import { __ } from "@wordpress/i18n";
import { useEffect, useState, useRef } from 'react';
import { Descendant } from "slate";
import { Link } from "react-router-dom";
import { useNavigate } from "react-router-dom";

import { Editor } from "../editor/Editor";
import useTemplateApi from '../../store/useTemplateApi';
import { useInput } from '../../store/useInput';
import { deserialize } from "../editor/utils";
import { useService } from '../../util/useService';
import { findErrorId, FieldError, ErrorSummary } from "./FieldError"

export const EditTemplate = () => {
    const navigate = useNavigate();
    const { templateId, getTemplate, saveTemplate, deleteTemplate } = useTemplateApi();
    const [currentTemplate, setCurrentTemplate] = useState<Descendant[]>();
    const [errors, setErrors] = useState([]);
    const { value: name, setValue: setName, bind: bindName } = useInput('');
    const { value: subject, setValue: setSubject, bind: bindSubject } = useInput('');
    const errorSummary = useRef(null);

    const { serviceId } = useService();
    useEffect(() => {
        const loadTemplate = async () => {
            if (templateId) {
                const template = await getTemplate(templateId);
                setName(template.name);
                setSubject(template.subject);
                setCurrentTemplate(deserialize(template.content || ""));
            }
        }
        loadTemplate();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    const handleSubmit = async () => {
        //@todo add error handling
        const errors = false;
        if (errors) {
            setErrors([]);
        }

        saveTemplate({ templateId, name, subject, content: currentTemplate })

        if (errorSummary?.current) {
            { /* @ts-ignore */ }
            errorSummary.current.focus();
        }

    }

    return (
        <>
            <div className="notice-container" role="alert" aria-atomic="true" tabIndex={-1} ref={errorSummary}>
                {
                    errors.length > 0 && <ErrorSummary errors={errors} />
                }
            </div>


            <form id="template">
                <table className="form-table">
                    <tbody>
                        <tr className="form-field form-required">
                            <th>
                                <label htmlFor="subject">
                                    {__("Name", "cds-snc")}
                                </label>
                            </th>
                            <td>
                                {/* The "id" needs to match the field ID attribute */}
                                <FieldError errors={errors} id={"name"}>
                                    { /* @ts-ignore */}
                                    <input type="text" id="name" aria-describedby={findErrorId(errors, "name") ? `validation-error--name` : null} {...bindName} />
                                </FieldError>
                            </td>
                        </tr>

                        <tr className="form-field form-required">
                            <th>
                                <label htmlFor="subject">
                                    {__("Subject", "cds-snc")}
                                </label>
                            </th>
                            <td>
                                {/* The "id" needs to match the field ID attribute */}
                                <FieldError errors={errors} id={"subject"}>
                                    { /* @ts-ignore */}
                                    <input type="text" id="subject" aria-describedby={findErrorId(errors, "subject") ? `validation-error--email` : null} {...bindSubject} />
                                </FieldError>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>

            {currentTemplate && <Editor template={currentTemplate} handleChange={setCurrentTemplate} />}
            <Link to={{ pathname: `/messages/${serviceId}/send/${templateId}` }}>{__('Send message to a list', 'cds-snc')}</Link>
            <button className="button" onClick={() => {
                handleSubmit();
            }}>{__('Save template', 'cds-snc')}</button>
            <button className="button" onClick={async () => {
                await deleteTemplate({ templateId });
                navigate(`/messages/${serviceId}`);
            }}>{__('Delete this message template', 'cds-snc')}</button>
        </>
    )
}

export default EditTemplate;