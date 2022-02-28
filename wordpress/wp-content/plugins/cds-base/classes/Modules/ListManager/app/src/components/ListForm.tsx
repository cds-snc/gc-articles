import * as React from 'react';
import { useEffect } from "react";
import { useForm } from "react-hook-form";
import { List, FieldError } from "../types";
import styled from 'styled-components';

const textWidth = { width: "25em" }


const Asterisk = () => {
    return (
        <>
            <span data-testid="asterisk" aria-hidden="true">* </span>
            <i style={{ display: "none" }} className="visually-hidden">Required Field</i>
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
        <form onSubmit={handleSubmit(handler)}>
            <table id="form-table" className="form-table">
                <tr>
                    <th><label className="required" htmlFor="name"><Asterisk />Name</label></th>
                    <td>
                        {errors.name && <p className="gc-error-message" role="alert">{errors.name?.message}</p>}
                        <input style={textWidth} type="text" {...register("name", { required: true })} />
                    </td>
                </tr>
                <tr>
                    <th><label className="required" htmlFor="language"><Asterisk />Language</label></th>
                    <td>
                        {errors.language && <p className="gc-error-message" role="alert">{errors.language?.message}</p>}
                        <input style={textWidth} type="text" {...register("language", { required: true })} />
                    </td>
                </tr>
                <tr>
                    <th><label className="required" htmlFor="service_id"><Asterisk />Service Id</label></th>
                    <td>
                        {errors.service_id && <p className="gc-error-message" role="alert">{errors.service_id?.message}</p>}
                        <input style={textWidth} type="text" {...register("service_id", { required: true })} />
                    </td>
                </tr>

                <tr>
                    <th><label className="required" htmlFor="subscribe_email_template_id">Subscribe email template id</label></th>
                    <td>
                        {errors.subscribe_email_template_id && <p className="gc-error-message" role="alert">{errors.subscribe_email_template_id?.message}</p>}
                        <input style={textWidth} type="text" {...register("subscribe_email_template_id")} />
                    </td>
                </tr>

                <tr>
                    <th><label className="required" htmlFor="unsubscribe_email_template_id">Unsubscribe email template id</label></th>
                    <td>
                        {errors.unsubscribe_email_template_id && <p className="gc-error-message" role="alert">{errors.unsubscribe_email_template_id?.message}</p>}
                        <input style={textWidth} type="text" {...register("unsubscribe_email_template_id")} />
                    </td>
                </tr>

                <tr>
                    <th><label className="required" htmlFor="subscribe_phone_template_id">Subscribe phone template id</label></th>
                    <td>
                        {errors.subscribe_phone_template_id && <p className="gc-error-message" role="alert">{errors.subscribe_phone_template_id?.message}</p>}
                        <input style={textWidth} type="text" {...register("subscribe_phone_template_id")} />
                    </td>
                </tr>

                <tr>
                    <th><label className="required" htmlFor="unsubscribe_phone_template_id">Unsubscribe phone template id</label></th>
                    <td>
                        {errors.unsubscribe_phone_template_id && <p className="gc-error-message" role="alert">{errors.unsubscribe_phone_template_id?.message}</p>}
                        <input style={textWidth} type="text" {...register("unsubscribe_phone_template_id")} />
                    </td>
                </tr>

                <tr>
                    <th><label className="gc-label required" htmlFor="subscribe_redirect_url">Subscribe redirect url</label></th>
                    <td>
                        {errors.subscribe_redirect_url && <p className="gc-error-message" role="alert">{errors.subscribe_redirect_url?.message}</p>}
                        <input style={textWidth} type="text" {...register("subscribe_redirect_url")} />
                    </td>
                </tr>

                <tr>
                    <th><label className="required" htmlFor="unsubscribe_redirect_url">Unsubscribe redirect url</label></th>
                    <td>
                        {errors.unsubscribe_redirect_url && <p className="gc-error-message" role="alert">{errors.unsubscribe_redirect_url?.message}</p>}
                        <input style={textWidth} type="text" {...register("unsubscribe_redirect_url")} />
                    </td>
                </tr>

                <tr>
                    <th><label className="required" htmlFor="confirm_redirect_url">Confirm redirect url</label></th>
                    <td>
                        {errors.confirm_redirect_url && <p className="gc-error-message" role="alert">{errors.confirm_redirect_url?.message}</p>}
                        <input style={textWidth} type="text" {...register("confirm_redirect_url")} />
                    </td>
                </tr>

                <tr>
                    <td><input className="button button-primary" type="submit" /></td>
                </tr>

            </table>
        </form>
    );
}