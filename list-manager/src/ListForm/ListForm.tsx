import React from "react";
import { useForm } from "react-hook-form";
import { Inputs } from "../types";

/*
<div>
              <input {...register("exampleRequired", { required: true })} />
              {errors.exampleRequired && <span>This field is required</span>}
          </div>
*/

export const ListForm = ({ handler }: { handler: any }) => {
    const { register, handleSubmit, formState: { errors } } = useForm<Inputs>();
    return (
        /* "handleSubmit" will validate your inputs before invoking "onSubmit" */
        <form onSubmit={handleSubmit(handler)}>
            <div>
                Name
                <input defaultValue="test" {...register("name")} />
            </div>
            <div>
                Language
                <input defaultValue="en" {...register("language")} />
            </div>
            <div>
                Service Id
                <input defaultValue="a7902fc7-37f0-419c-84c8-3ab499ee24c8" {...register("service_id")} />
            </div>

            <div>
                Subscribe email template id
                <input defaultValue="4c19c576-3cb0-452f-a573-fb6b126b680f" {...register("subscribe_email_template_id")} />
            </div>

            <div>
                Subscribe redirect url
                <input defaultValue="https://articles.cdssandbox.xyz" {...register("subscribe_redirect_url")} />
            </div>

            <input type="submit" />
        </form>
    );
}