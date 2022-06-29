/**
 * External dependencies
 */
import * as React from 'react';
import { useEffect } from "react";
import { useForm } from "react-hook-form";
import { __ } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import { Back } from "./Back";
import { List, FieldError } from "../../types";
import { useList } from "../../store";

const textWidth = { width: "25em" }

const Asterisk = () => {
    return (
        <>
            <span data-testid="asterisk" aria-hidden="true">* </span>
            <i style={{ display: "none" }} className="visually-hidden">{__("Required Field", "gc-lists")}</i>
        </>
    )
}

export const ListForm = ({ handler, formData = {}, serverErrors = [] }: { handler: (list: List) => void, formData: {} | List, serverErrors: FieldError[] }) => {
    const { state: { user } } = useList();
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
            {/* @todo np phone access use default language field will be replaced with "list_type" field */}
            {!user?.hasPhone && <input type="hidden" name="language" value="en" />}
            <table id="form-table" className="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label className="required" htmlFor="name"><Asterisk />{__("Name", "gc-lists")}</label></th>
                        <td>
                            <div className={errors.name ? "error-wrapper" : ""}>
                                {errors.name && <span className="validation-error">{errors.name?.message || __("Name is required", "gc-lists")}</span>}
                                <input id="name" style={textWidth} type="text" {...register("name", { required: true })} />
                            </div>
                        </td>
                        {/* @todo language field will be replaced with "list_type" field */}
                    </tr>
                    {
                        user?.hasPhone && <tr>
                            <th scope="row"><label className="required" htmlFor="language"><Asterisk />{__("List type", "gc-lists")}</label></th>
                            <td>
                                <div className={errors.language ? "error-wrapper" : ""}>
                                    {errors.language && <span className="validation-error">{errors.language?.message || __("Type is required", "gc-lists")}</span>}
                                    <fieldset>
                                        <label htmlFor="en">
                                            <input id="en" {...register("language", { required: true })} type="radio" value="en" />
                                            {" "}Email
                                        </label>
                                        <br />
                                        <label htmlFor="fr">
                                            <input id="fr" {...register("language", { required: true })} type="radio" value="fr" />
                                            {" "}Phone
                                        </label>
                                    </fieldset>
                                </div>
                            </td>
                        </tr>}
                    <tr>
                        <th scope="row"><label htmlFor="subscribe_email_template_id">{__("Subscribe template id", "gc-lists")}</label></th>
                        <td>
                            <div className={errors.subscribe_email_template_id ? "error-wrapper" : ""}>
                                {errors.subscribe_email_template_id && <span className="validation-error">{errors.subscribe_email_template_id?.message}</span>}
                                <input id="subscribe_email_template_id" style={textWidth} type="text" {...register("subscribe_email_template_id")} />
                                <div className="role-desc description">
                                    <details>
                                        <summary>{__("See example template ID format.", "gc-lists")}</summary><code>ex4mp1e0-d248-4661-a3d6-0647167e3720</code>
                                    </details>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label htmlFor="unsubscribe_email_template_id">{__("Unsubscribe template id", "gc-lists")}</label></th>
                        <td>
                            <div className={errors.unsubscribe_email_template_id ? "error-wrapper" : ""}>
                                {errors.unsubscribe_email_template_id && <span className="validation-error">{errors.unsubscribe_email_template_id?.message}</span>}
                                <input id="unsubscribe_email_template_id" style={textWidth} type="text" {...register("unsubscribe_email_template_id")} />
                                <div className="role-desc description">
                                    <details>
                                        <summary>{__("See example template ID format.", "gc-lists")}</summary><code>ex4mp1e0-d248-4661-a3d6-0647167e3720</code>
                                    </details>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label className="gc-label" htmlFor="subscribe_redirect_url">{__("Subscribe redirect url", "gc-lists")}</label></th>
                        <td>
                            <div className={errors.subscribe_redirect_url ? "error-wrapper" : ""}>
                                {errors.subscribe_redirect_url && <span className="validation-error">{errors.subscribe_redirect_url?.message}</span>}
                                <input id="subscribe_redirect_url" style={textWidth} type="text" {...register("subscribe_redirect_url")} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label htmlFor="unsubscribe_redirect_url">{__("Unsubscribe redirect url", "gc-lists")}</label></th>
                        <td>
                            <div className={errors.unsubscribe_redirect_url ? "error-wrapper" : ""}>
                                {errors.unsubscribe_redirect_url && <span className="validation-error">{errors.unsubscribe_redirect_url?.message}</span>}
                                <input id="unsubscribe_redirect_url" style={textWidth} type="text" {...register("unsubscribe_redirect_url")} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label htmlFor="confirm_redirect_url">{__("Confirm redirect url", "gc-lists")}</label></th>
                        <td>
                            <div className={errors.confirm_redirect_url ? "error-wrapper" : ""}>
                                {errors.confirm_redirect_url && <span className="validation-error">{errors.confirm_redirect_url?.message}</span>}
                                <input id="confirm_redirect_url" style={textWidth} type="text" {...register("confirm_redirect_url")} />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input className="button button-primary" type="submit" /><Back /></td>
                    </tr>
                </tbody>
            </table>
        </form >
    );
}
