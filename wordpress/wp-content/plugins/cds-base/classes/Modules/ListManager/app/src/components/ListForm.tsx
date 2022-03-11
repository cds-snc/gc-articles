import * as React from 'react';
import { useEffect } from "react";
import { useForm } from "react-hook-form";
import { List, FieldError } from "../types";
import { __ } from "@wordpress/i18n";

const textWidth = { width: "25em" }

const Asterisk = () => {
    return (
        <>
            <span data-testid="asterisk" aria-hidden="true">* </span>
            <i style={{ display: "none" }} className="visually-hidden">{__("Required Field", "cds-snc")}</i>
        </>
    )
}

export const ListForm = ({ handler, formData = {}, serverErrors = [] }: { handler: (list: List) => void, formData: {} | List, serverErrors: FieldError[] }) => {
    const { register, handleSubmit, setError, formState: { errors } } = useForm<List>({ defaultValues: formData });

    useEffect(() => {
        serverErrors && serverErrors.length >= 1 && serverErrors.forEach((item) => {

            setError(item?.name, {
                type: "manual",
                message: item.msg,
            });

        });
    }, [setError, serverErrors]);

    return (
        <form onSubmit={handleSubmit(handler)}>
            <table id="form-table" className="form-table">
                <thead>
                    <tr>
                        <th><label className="required" htmlFor="name"><Asterisk />{__("Name", "cds-snc")}</label></th>
                        <td>
                            <div className={errors.name ? "error-wrapper" : ""}>
                                {errors.name && <span className="validation-error">{errors.name?.message || __("Name is required", "cds-snc")}</span>}
                                <input style={textWidth} type="text" {...register("name", { required: true })} />
                            </div>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><label className="required" htmlFor="language"><Asterisk />{__("Language", "cds-snc")}</label></th>
                        <td>
                            <div className={errors.language ? "error-wrapper" : ""}>
                                {errors.language && <span className="validation-error">{errors.language?.message || __("Language is required", "cds-snc")}</span>}
                                <input style={textWidth} type="text" {...register("language", { required: true })} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label className="required" htmlFor="service_id"><Asterisk />{__("Service Id", "cds-snc")}</label></th>
                        <td>
                            <div className={errors.service_id ? "error-wrapper" : ""}>
                                {errors.service_id && <span className="validation-error">{errors.service_id?.message || __("Service ID is required", "cds-snc")}</span>}
                                <input style={textWidth} type="text" {...register("service_id", { required: true })} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label className="required" htmlFor="subscribe_email_template_id">{__("Subscribe email template id", "cds-snc")}</label></th>
                        <td>
                            <div className={errors.subscribe_email_template_id ? "error-wrapper" : ""}>
                                {errors.subscribe_email_template_id && <span className="validation-error">{errors.subscribe_email_template_id?.message}</span>}
                                <input style={textWidth} type="text" {...register("subscribe_email_template_id")} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label className="required" htmlFor="unsubscribe_email_template_id">{__("Unsubscribe email template id", "cds-snc")}</label></th>
                        <td>
                            <div className={errors.unsubscribe_email_template_id ? "error-wrapper" : ""}>
                                {errors.unsubscribe_email_template_id && <span className="validation-error">{errors.unsubscribe_email_template_id?.message}</span>}
                                <input style={textWidth} type="text" {...register("unsubscribe_email_template_id")} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label className="required" htmlFor="subscribe_phone_template_id">{__("Subscribe phone template id", "cds-snc")}</label></th>
                        <td>
                            <div className={errors.subscribe_phone_template_id ? "error-wrapper" : ""}>
                                {errors.subscribe_phone_template_id && <span className="validation-error">{errors.subscribe_phone_template_id?.message}</span>}
                                <input style={textWidth} type="text" {...register("subscribe_phone_template_id")} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label className="required" htmlFor="unsubscribe_phone_template_id">{__("Unsubscribe phone template id", "cds-snc")}</label></th>
                        <td>
                            <div className={errors.unsubscribe_phone_template_id ? "error-wrapper" : ""}>
                                {errors.unsubscribe_phone_template_id && <span className="validation-error">{errors.unsubscribe_phone_template_id?.message}</span>}
                                <input style={textWidth} type="text" {...register("unsubscribe_phone_template_id")} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label className="gc-label required" htmlFor="subscribe_redirect_url">{__("Subscribe redirect url", "cds-snc")}</label></th>
                        <td>
                            <div className={errors.subscribe_redirect_url ? "error-wrapper" : ""}>
                                {errors.subscribe_redirect_url && <span className="validation-error">{errors.subscribe_redirect_url?.message}</span>}
                                <input style={textWidth} type="text" {...register("subscribe_redirect_url")} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label className="required" htmlFor="unsubscribe_redirect_url">{__("Unsubscribe redirect url", "cds-snc")}</label></th>
                        <td>
                            <div className={errors.unsubscribe_redirect_url ? "error-wrapper" : ""}>
                                {errors.unsubscribe_redirect_url && <span className="validation-error">{errors.unsubscribe_redirect_url?.message}</span>}
                                <input style={textWidth} type="text" {...register("unsubscribe_redirect_url")} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label className="required" htmlFor="confirm_redirect_url">{__("Confirm redirect url", "cds-snc")}</label></th>
                        <td>
                            <div className={errors.confirm_redirect_url ? "error-wrapper" : ""}>
                                {errors.confirm_redirect_url && <span className="validation-error">{errors.confirm_redirect_url?.message}</span>}
                                <input style={textWidth} type="text" {...register("confirm_redirect_url")} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><input className="button button-primary" type="submit" /></td>
                    </tr>
                </tbody>
            </table>
        </form>
    );
}