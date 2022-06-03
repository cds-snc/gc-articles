import * as React from 'react';
import { __ } from "@wordpress/i18n";
import { useEffect, useState, useCallback } from 'react';
import { Descendant } from "slate";
import { Link, useNavigate } from 'react-router-dom';

import { Editor } from "../editor/Editor";
import useTemplateApi from '../../store/useTemplateApi';
import { deserialize, serialize } from '../editor/utils';
import { useForm } from "react-hook-form";
import { Success } from "./Notice";
import { Spinner } from '../../common/Spinner';
import styled from 'styled-components';
import formatRelative from 'date-fns/formatRelative';

const textWidth = { width: "25em" }

export const StyledLastSaved = styled.div`
   font-size:16px;
   display:flex;
   justify-content: flex-end;
`;

export const StyledPreviousVersions = styled.span`
   display:inline-block;
   margin-left:10px;
`;

export const EditTemplate = () => {
    const navigate = useNavigate();
    const [saved, setSaved] = useState(false);
    const { template, loadingTemplate, templateId, getTemplate, saveTemplate } = useTemplateApi();
    const [currentTemplate, setCurrentTemplate] = useState<Descendant[]>();

    const { register, setValue, getValues, clearErrors, handleSubmit, formState: { errors } } = useForm({ defaultValues: { name: "", subject: "", hasTemplate: "" } });
    useEffect(() => {
        setValue("name", template?.name || "")
        setValue("subject", template?.subject || "");
    }, [template, setValue]);

    useEffect(() => {
        const fetchTemplate = async () => {
            await getTemplate(templateId);
        }

        fetchTemplate();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    const handleFormData = useCallback(async (formData: any) => {
        let content = template?.body;

        if (currentTemplate) {
            content = serialize(currentTemplate)
        }

        setSaved(false);
        const { name, subject } = formData;
        const result = await saveTemplate({ templateId, name, subject, content: deserialize(content) });

        if (result) {
            navigate(`/messages/edit/${result?.id}`);
            setSaved(true);
        }

    }, [saveTemplate, currentTemplate, templateId, navigate, template]);

    const heading = <h1>{__("Edit email message", "cds-snc")}</h1>;

    if (loadingTemplate) {
        return (
            <>
                {heading}
                <Spinner />
            </>
        )
    }

    const templateHasValue = currentTemplate && serialize(currentTemplate) !== '';

    let content = template?.body;

    if (currentTemplate) {
        content = serialize(currentTemplate)
    }

    return (
        <>
            {heading}
            <form style={{ maxWidth: '400px' }}>
                <input type="hidden" {...register("hasTemplate", { validate: () => templateHasValue })} />
                {
                    /*
                    ☝️☝️☝️☝️☝️☝️
                    hasTemplate (hidden field)
                    this is for validation only
                    ...we don't register the "editor / content " as part of the form
                    but we need to ensure it has content
                    */
                }
                <table className="form-table">
                    <tbody>
                        <tr>
                            <td>
                                <label className="required" htmlFor="name"><strong>{__("Message name", "cds-snc")}</strong></label>
                                <p>{__("Your recipients will not see this message name.", "cds-snc")}</p>
                                <div className={errors.name ? "error-wrapper" : ""}>
                                    {errors.name && <span className="validation-error">{errors.name?.message || __("Name is required", "cds-snc")}</span>}
                                    <input id="name" style={textWidth} type="text" {...register("name", { required: true })} />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label className="required" htmlFor="subject"><strong>{__("Subject line of the email", "cds-snc")}</strong></label>
                                <p style={{ marginBottom: "12px" }}>{__("Tell recipients what the message is about. Try to keep it shorter than 10 words.", "cds-snc")}</p>
                                <div className={errors.subject ? "error-wrapper" : ""}>
                                    {errors.subject && <span className="validation-error">{errors.subject?.message || __("Subject is required", "cds-snc")}</span>}
                                    <input id="subject" style={textWidth} type="text" {...register("subject", { required: true })} />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label className="required" htmlFor="message"><strong>{__("Message", "cds-snc")}</strong></label>
                                <p style={{ marginBottom: '10px' }}>{__("Use the", "cds-snc")} <a href="https://notification.canada.ca/formatting-guide">{__("email formatting guide", "cds-snc")}</a> {__("(Opens in a new tab) to craft your message.", "cds-snc")}</p>
                                <div className={errors.hasTemplate ? "error-wrapper" : ""}>
                                    {errors.hasTemplate && <span className="validation-error">{errors.hasTemplate?.message || __("Message is required", "cds-snc")}</span>}
                                    {template.parsedContent ?
                                        <Editor template={template.parsedContent}
                                            handleValidate={(value: any) => {
                                                clearErrors("hasTemplate");
                                            }}
                                            handleChange={setCurrentTemplate} />
                                        : null}
                                </div>
                                <StyledLastSaved>
                                    {/* @todo use date-fns to show template date */}
                                    {template?.updated_at ? <> {__('Last saved', "cds-snc")} {formatRelative(new Date(template.updated_at), new Date())} </> : null}
                                    {templateId && <Link to={`/messages/${templateId}/versions`}> <StyledPreviousVersions>{__('See previous versions', "cds-snc")}</StyledPreviousVersions></Link>}
                                </StyledLastSaved>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>


            {saved && <Success message={"Message saved"} />}

            <div>
                <button style={{ marginRight: "20px" }}
                    onClick={async () => {
                        navigate(`/messages/send/${templateId}`, { state: { ...getValues(), template: content } });
                    }}
                    className="button button-primary">
                    {__('Choose a list to send to', 'cds-snc')}
                </button>

                <button className="button" onClick={async () => {
                    handleSubmit(handleFormData, () => {
                        return false;
                    })();
                }}>{__('Save for later', 'cds-snc')}</button>
            </div>
        </>
    )
}

export default EditTemplate;
