import React from "react";
import { useForm } from "react-hook-form";
import { Inputs } from "../types";

export const ListForm = ({ handler = "", formData = {} }: { handler: any, formData: any }) => {
    
    console.log(formData)
    
    const { register, handleSubmit, formState: { errors } } = useForm<Inputs>({ defaultValues: formData });

    const getVal = (name = ""): string => {
        return formData?.[name] ? formData?.[name] : "";
    }

    return (
        <form onSubmit={handleSubmit(handler)}>
            <div className="form-field">
                <label htmlFor="name">Name</label>
                <input {...register("name", { required: true })} />
            </div>
            <div className="form-field">
                <label htmlFor="language">Language</label>
                <input {...register("language", { required: true })} />
            </div>
            <div className="form-field">
                <label htmlFor="service_id">Service Id</label>
                <input {...register("service_id", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor="subscribe_email_template_id">Subscribe email template id</label>
                <input {...register("subscribe_email_template_id", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor="unsubscribe_email_template_id">Unsubscribe email template id</label>
                <input {...register("unsubscribe_email_template_id", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor="subscribe_redirect_url">Subscribe redirect url</label>
                <input {...register("subscribe_redirect_url", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor="unsubscribe_redirect_url">Unsubscribe redirect url</label>
                <input {...register("unsubscribe_redirect_url", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor="subscribe_phone_template_id">Subscribe phone template id</label>
                <input {...register("subscribe_phone_template_id", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor="unsubscribe_phone_template_id">Unsubscribe phone template id</label>
                <input {...register("unsubscribe_phone_template_id", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor="confirm_redirect_url">Confirm redirect url</label>
                <input {...register("confirm_redirect_url", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor=""></label>
                <input type="submit" />
            </div>
        </form>
    );
}