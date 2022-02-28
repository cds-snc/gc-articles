import * as React from 'react';
import { useEffect } from "react";
import { useForm } from "react-hook-form";
import { List, FieldError } from "../types";

const Asterisk = () => {
    return (
        <>
            <span data-testid="asterisk" aria-hidden="true">* </span>
            <i className="visually-hidden">Required Field</i>
        </>
    )
}

export const ListForm = ({ handler, formData = {}, serverErrors = [] }: { handler: (list: List) => void, formData: {} | List, serverErrors: FieldError[] }) => {
    const { register, handleSubmit, setError, formState: { errors } } = useForm<List>({ defaultValues: formData });

    useEffect(() => {
        console.log(serverErrors)
        serverErrors && serverErrors.length >= 1 && serverErrors.forEach((item) => {

            setError(item?.name, {
                type: "manual",
                message: item.msg,
            });

        });
    }, [setError, serverErrors]);

    return (
        <div id="gc-form-wrapper" className="gc-form-wrapper">
            <form onSubmit={handleSubmit(handler)}>
                <div className="focus-group">
                    <label className="gc-label required" htmlFor="name"><Asterisk />Name</label>
                    {errors.name && <p className="gc-error-message" role="alert">{errors.name?.message}</p>}
                    <input className="gc-input-text" {...register("name", { required: true })} />
                </div>
                <div className="focus-group">
                    <label className="gc-label required" htmlFor="language"><Asterisk />Language</label>
                    {errors.language && <p className="gc-error-message" role="alert">{errors.language?.message}</p>}
                    <input className="gc-input-text" {...register("language", { required: true })} />
                </div>
                <div className="focus-group">
                    <label className="gc-label required" htmlFor="service_id"><Asterisk />Service Id</label>
                    {errors.service_id && <p className="gc-error-message" role="alert">{errors.service_id?.message}</p>}
                    <input className="gc-input-text" {...register("service_id", { required: true })} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="subscribe_email_template_id">Subscribe email template id</label>
                    {errors.subscribe_email_template_id && <p className="gc-error-message" role="alert">{errors.subscribe_email_template_id?.message}</p>}
                    <input className="gc-input-text" {...register("subscribe_email_template_id")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="unsubscribe_email_template_id">Unsubscribe email template id</label>
                    {errors.unsubscribe_email_template_id && <p className="gc-error-message" role="alert">{errors.unsubscribe_email_template_id?.message}</p>}
                    <input className="gc-input-text" {...register("unsubscribe_email_template_id")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="subscribe_phone_template_id">Subscribe phone template id</label>
                    {errors.subscribe_phone_template_id && <p className="gc-error-message" role="alert">{errors.subscribe_phone_template_id?.message}</p>}
                    <input className="gc-input-text" {...register("subscribe_phone_template_id")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="unsubscribe_phone_template_id">Unsubscribe phone template id</label>
                    {errors.unsubscribe_phone_template_id && <p className="gc-error-message" role="alert">{errors.unsubscribe_phone_template_id?.message}</p>}
                    <input className="gc-input-text" {...register("unsubscribe_phone_template_id")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="subscribe_redirect_url">Subscribe redirect url</label>
                    {errors.subscribe_redirect_url && <p className="gc-error-message" role="alert">{errors.subscribe_redirect_url?.message}</p>}
                    <input className="gc-input-text" {...register("subscribe_redirect_url")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="unsubscribe_redirect_url">Unsubscribe redirect url</label>
                    {errors.unsubscribe_redirect_url && <p className="gc-error-message" role="alert">{errors.unsubscribe_redirect_url?.message}</p>}
                    <input className="gc-input-text" {...register("unsubscribe_redirect_url")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="confirm_redirect_url">Confirm redirect url</label>
                    {errors.confirm_redirect_url && <p className="gc-error-message" role="alert">{errors.confirm_redirect_url?.message}</p>}
                    <input className="gc-input-text" {...register("confirm_redirect_url")} />
                </div>

                <div className="focus-group">
                    <input className="gc-button" type="submit" />
                </div>
            </form>
        </div>
    );
}