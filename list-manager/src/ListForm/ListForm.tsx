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
            <div>
                Name !
                <input defaultValue={getVal("name")} {...register("name", { required: true })} />
            </div>
            <div>
                Language
                <input defaultValue={getVal("language")} {...register("language", { required: true })} />
            </div>
            <div>
                Service Id
                <input defaultValue={getVal("service_id")} {...register("service_id", { required: true })} />
            </div>

            <div>
                Subscribe email template id
                <input defaultValue={getVal("subscribe_email_template_id")} {...register("subscribe_email_template_id", { required: true })} />
            </div>

            <div>
                Subscribe redirect url
                <input defaultValue={getVal("subscribe_redirect_url")} {...register("subscribe_redirect_url", { required: true })} />
            </div>

            <input type="submit" />
        </form>
    );
}