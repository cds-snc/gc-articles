import React from "react";
import { useForm } from "react-hook-form";
import { Inputs } from "../types";

export const ListForm = ({ handler = "", formData = {} }: { handler: any, formData: any }) => {
    const { register, handleSubmit, formState: { errors } } = useForm<Inputs>();

    const getVal = (name = ""): string => {
        return formData?.[name] ? formData?.[name] : "";
    }

    return (
        <form onSubmit={handleSubmit(handler)}>
            <div className="form-field">
                <label htmlFor="name">Name</label>
                <input defaultValue={getVal("name")} {...register("name", { required: true })} />
            </div>
            <div className="form-field">
                <label htmlFor="language">Language</label>
                <input defaultValue={getVal("language")} {...register("language", { required: true })} />
            </div>
            <div className="form-field">
                <label htmlFor="service_id">Service Id</label>
                <input defaultValue={getVal("service_id")} {...register("service_id", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor="subscribe_email_template_id">Subscribe email template id</label>
                <input defaultValue={getVal("subscribe_email_template_id")} {...register("subscribe_email_template_id", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor="subscribe_redirect_url">Subscribe redirect url</label>
                <input defaultValue={getVal("subscribe_redirect_url")} {...register("subscribe_redirect_url", { required: true })} />
            </div>

            <div className="form-field">
                <label htmlFor=""></label>
                <input type="submit" />
            </div>
        </form>
    );
}