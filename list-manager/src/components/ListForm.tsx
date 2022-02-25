import { useForm } from "react-hook-form";

import { List } from "../types";

const Asterisk = () => {
    return (
        <>
            <span data-testid="asterisk" aria-hidden="true">* </span>
            <i className="visually-hidden">Required Field</i>
        </>
    )
}

export const ListForm = ({ handler = "", formData = {} }: { handler: any, formData: any }) => {
    const { register, handleSubmit, formState: { errors } } = useForm<List>({ defaultValues: formData });

    return (
        <div id="gc-form-wrapper" className="gc-form-wrapper">
            <form onSubmit={handleSubmit(handler)}>
                <div className="focus-group">
                    <label className="gc-label required" htmlFor="name"><Asterisk />Name</label>
                    {errors.name && <p className="gc-error-message" role="alert">This field is required</p>}
                    <input className="gc-input-text" {...register("name", { required: true })} />
                </div>
                <div className="focus-group">
                    <label className="gc-label required" htmlFor="language"><Asterisk />Language</label>
                    {errors.language && <p className="gc-error-message" role="alert">This field is required</p>}
                    <input className="gc-input-text" {...register("language", { required: true })} />
                </div>
                <div className="focus-group">
                    <label className="gc-label required" htmlFor="service_id"><Asterisk />Service Id</label>
                    {errors.service_id && <p className="gc-error-message" role="alert">This field is required</p>}
                    <input className="gc-input-text" {...register("service_id", { required: true })} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="subscribe_email_template_id">Subscribe email template id</label>
                    <input className="gc-input-text" {...register("subscribe_email_template_id")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="unsubscribe_email_template_id">Unsubscribe email template id</label>
                    <input className="gc-input-text" {...register("unsubscribe_email_template_id")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="subscribe_phone_template_id">Subscribe phone template id</label>
                    <input className="gc-input-text" {...register("subscribe_phone_template_id")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="unsubscribe_phone_template_id">Unsubscribe phone template id</label>
                    <input className="gc-input-text" {...register("unsubscribe_phone_template_id")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="subscribe_redirect_url">Subscribe redirect url</label>
                    <input className="gc-input-text" {...register("subscribe_redirect_url")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="unsubscribe_redirect_url">Unsubscribe redirect url</label>
                    <input className="gc-input-text" {...register("unsubscribe_redirect_url")} />
                </div>

                <div className="focus-group">
                    <label className="gc-label required" htmlFor="confirm_redirect_url">Confirm redirect url</label>
                    <input className="gc-input-text" {...register("confirm_redirect_url")} />
                </div>

                <div className="focus-group">
                    <input className="gc-button" type="submit" />
                </div>
            </form>
        </div>
    );
}