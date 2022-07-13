/**
 * External dependencies
 */
import { __ } from "@wordpress/i18n";
import { useEffect, useState, useCallback } from 'react';
import { Descendant } from "slate";
import { useNavigate } from 'react-router-dom';
import { useForm } from "react-hook-form";
import styled from 'styled-components';

/**
 * Internal dependencies
 */
import { Editor, deserialize, serialize } from '../editor';
import { useTemplateApi } from '../../store';
import { Success, Spinner, StyledLink, Back } from "../components";

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

export const StyledCell = styled.td`
    padding-left: 0;

    label {
        display: block;
        margin-bottom: 10px;
    }

    label + p {
        margin-top: -6px;
        margin-bottom: 10px;
    }
`

export const EditMessage = () => {
    const navigate = useNavigate();
    const [saved, setSaved] = useState(false);
    const { template, loadingTemplate, templateId, messageType, getTemplate, saveTemplate } = useTemplateApi();
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
        const result = await saveTemplate({ templateId, name, subject, content: deserialize(content), message_type: messageType });

        if (result) {
            navigate(`/messages`, { state: { type: "saved", from: 'edit-template', ...result } });
            setSaved(true);
        }

    }, [saveTemplate, currentTemplate, templateId, navigate, template]);

    const heading = messageType === 'phone' ? __("Edit text message", "gc-lists") : __("Edit email message", "gc-lists");

    if (loadingTemplate) {
        return (
            <>
                <h1>{heading}</h1>
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
            <StyledLink to={`/messages`}>
                <Back /> <span>{__("Back to messages ", "gc-lists")}</span>
            </StyledLink>
            <h1>{heading}</h1>
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
                            <StyledCell>
                                <label className="required" htmlFor="name"><strong>{__("Message name", "gc-lists")}</strong></label>
                                <p>{__("Your recipients will not see this message name.", "gc-lists")}</p>
                                <div className={errors.name ? "error-wrapper" : ""}>
                                    {errors.name && <span className="validation-error">{errors.name?.message || __("Name is required", "gc-lists")}</span>}
                                    <input id="name" style={textWidth} type="text" {...register("name", { required: true })} />
                                </div>
                            </StyledCell>
                        </tr>
                        {messageType !== 'phone' &&
                            <tr>
                            <StyledCell>
                                <label className="required" htmlFor="subject"><strong>{__("Subject line of the email", "gc-lists")}</strong></label>
                                <p>{__("Tell recipients what the message is about. Try to keep it shorter than 10 words.", "gc-lists")}</p>
                                <div className={errors.subject ? "error-wrapper" : ""}>
                                    {errors.subject && <span className="validation-error">{errors.subject?.message || __("Subject is required", "gc-lists")}</span>}
                                    <input id="subject" style={textWidth} type="text" {...register("subject", { required: true })} />
                                </div>
                            </StyledCell>
                        </tr>
                        }
                        <tr>
                            <StyledCell>
                                <label className="required" htmlFor="message"><strong>{__("Message", "gc-lists")}</strong></label>
                                {messageType !== 'phone' &&
                                    <p>{__("Use the", "gc-lists")} <a href="https://notification.canada.ca/formatting-guide" target="_blank" rel="noreferrer">{__("email formatting guide", "gc-lists")}</a> {__("(Opens in a new tab) to craft your message.", "gc-lists")}</p>
                                }
                                <div className={errors.hasTemplate ? "error-wrapper" : ""}>
                                    {errors.hasTemplate && <span className="validation-error">{errors.hasTemplate?.message || __("Message is required", "gc-lists")}</span>}
                                    {template.parsedContent ?
                                        <Editor template={template.parsedContent}
                                            handleValidate={(value: any) => {
                                                clearErrors("hasTemplate");
                                            }}
                                            handleChange={setCurrentTemplate} />
                                        : null}
                                </div>
                                {/*<StyledLastSaved>*/}
                                {/*    /!* @todo use date-fns to show template date *!/*/}
                                {/*    {template?.updated_at ? <> {__('Last saved', "gc-lists")} {formatRelative(new Date(template.updated_at), new Date())} </> : null}*/}
                                {/*    {templateId && <Link to={`/messages/${templateId}/versions`}> <StyledPreviousVersions>{__('See previous versions', "gc-lists")}</StyledPreviousVersions></Link>}*/}
                                {/*</StyledLastSaved>*/}
                            </StyledCell>
                        </tr>
                    </tbody>
                </table>
            </form>
            {saved && <Success message={__("Message saved", 'gc-lists')} />}
            <div>
                <button style={{ marginRight: "20px" }}
                    onClick={async () => {
                        navigate(`/messages/send/${templateId}`, { state: { ...getValues(), template: content } });
                    }}
                    className="button button-primary">
                    {__('Choose a list to send to', 'gc-lists')}
                </button>

                <button className="button" onClick={async () => {
                    handleSubmit(handleFormData, () => {
                        return false;
                    })();
                }}>{__('Save draft', 'gc-lists')}</button>
            </div>
        </>
    )
}

export default EditMessage;
