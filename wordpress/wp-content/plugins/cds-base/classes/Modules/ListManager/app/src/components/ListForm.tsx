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
            <input id="service_id" type="hidden" {...register("service_id", { required: true })} />
            <table id="form-table" className="form-table">
                <tr>
                    <th><label className="required" htmlFor="name"><Asterisk />{__("Name", "cds-snc")}</label></th>
                    <td>
                        <div className={errors.name ? "error-wrapper" : ""}>
                            {errors.name && <span className="validation-error">{errors.name?.message || __("Name is required", "cds-snc")}</span>}
                            <input id="name" style={textWidth} type="text" {...register("name", { required: true })} />
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><label className="required" htmlFor="language"><Asterisk />{__("Language", "cds-snc")}</label></th>
                    <td>
                        <div className={errors.language ? "error-wrapper" : ""}>
                            {errors.language && <span className="validation-error">{errors.language?.message || __("Language is required", "cds-snc")}</span>}
                            <input id="language" style={textWidth} type="text" {...register("language", { required: true })} />
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><label className="required" htmlFor="subscribe_email_template_id">{__("Subscribe email template id", "cds-snc")}</label></th>
                    <td>
                        <div className={errors.subscribe_email_template_id ? "error-wrapper" : ""}>
                            {errors.subscribe_email_template_id && <span className="validation-error">{errors.subscribe_email_template_id?.message}</span>}
                            <input id="subscribe_email_template_id" style={textWidth} type="text" {...register("subscribe_email_template_id")} />
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><label className="required" htmlFor="unsubscribe_email_template_id">{__("Unsubscribe email template id", "cds-snc")}</label></th>
                    <td>
                        <div className={errors.unsubscribe_email_template_id ? "error-wrapper" : ""}>
                            {errors.unsubscribe_email_template_id && <span className="validation-error">{errors.unsubscribe_email_template_id?.message}</span>}
                            <input id="unsubscribe_email_template_id" style={textWidth} type="text" {...register("unsubscribe_email_template_id")} />
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><label className="gc-label required" htmlFor="subscribe_redirect_url">{__("Subscribe redirect url", "cds-snc")}</label></th>
                    <td>
                        <div className={errors.subscribe_redirect_url ? "error-wrapper" : ""}>
                            {errors.subscribe_redirect_url && <span className="validation-error">{errors.subscribe_redirect_url?.message}</span>}
                            <input id="subscribe_redirect_url" style={textWidth} type="text" {...register("subscribe_redirect_url")} />
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><label className="required" htmlFor="unsubscribe_redirect_url">{__("Unsubscribe redirect url", "cds-snc")}</label></th>
                    <td>
                        <div className={errors.unsubscribe_redirect_url ? "error-wrapper" : ""}>
                            {errors.unsubscribe_redirect_url && <span className="validation-error">{errors.unsubscribe_redirect_url?.message}</span>}
                            <input id="unsubscribe_redirect_url" style={textWidth} type="text" {...register("unsubscribe_redirect_url")} />
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><label className="required" htmlFor="confirm_redirect_url">{__("Confirm redirect url", "cds-snc")}</label></th>
                    <td>
                        <div className={errors.confirm_redirect_url ? "error-wrapper" : ""}>
                            {errors.confirm_redirect_url && <span className="validation-error">{errors.confirm_redirect_url?.message}</span>}
                            <input id="confirm_redirect_url" style={textWidth} type="text" {...register("confirm_redirect_url")} />
                        </div>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td><input className="button button-primary" type="submit" /></td>
                </tr>
            </table>
        </form>
    );
}