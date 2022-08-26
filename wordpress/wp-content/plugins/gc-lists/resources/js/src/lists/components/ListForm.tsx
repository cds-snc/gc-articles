/**
 * External dependencies
 */
import { useEffect } from "react";
import { useForm } from "react-hook-form";
import { __ } from "@wordpress/i18n";
import styled from 'styled-components';
import { useNavigate } from 'react-router-dom';

/**
 * Internal dependencies
 */
import { List, FieldError as ErrorType } from "../../types";
import { useList, useService } from "../../store";

import { FieldError } from '../../messages/components';

const StyledCell = styled.td`
    padding-left: 0 !important;

    input[type="radio"] + label {
        display: inline-block;
        margin: 2px 0 5px 3px;
    }
`

const StyledDetails = styled.details`
    padding: 20px 10px 20px 0;

    summary > span {
        text-decoration: underline;
    }
`

const textWidth = { width: "25em" }

export const ListForm = ({ handler, formData = {}, serverErrors = [] }: { handler: (list: List) => void, formData: {} | List, serverErrors: ErrorType[] }) => {
    const navigate = useNavigate();
    const { state: { user } } = useList();
    const { register, handleSubmit, setError, formState: { errors } } = useForm<List>({ defaultValues: formData });
    const { subscribeTemplate } = useService();
    const isNewList = !("name" in formData) // if the "name" exists, we are editing the list
    const saveButtonText = isNewList ? __("Save and continue", "gc-lists") : __("Save", "gc-lists");

    useEffect(() => {
        serverErrors && serverErrors.length >= 1 && serverErrors.forEach((item) => {

            setError(item?.name, {
                type: "manual",
                message: item.msg,
            });

        });
    }, [setError, serverErrors]);

    return (
        <form style={{ maxWidth: '700px' }} onSubmit={handleSubmit(handler)}>

            {/* @todo np phone access use default language field will be replaced with "list_type" field */}
            {!user?.hasPhone && <input type="hidden" name="language" value="en" />}

            {/* Optional hidden field, pulled from value entered on the settings panel if set */}
            <input id="subscribe_email_template_id" type="hidden" value={ subscribeTemplate } {...register("subscribe_email_template_id")} />

            <table className="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label htmlFor="name">{__("List name", "gc-lists")}</label>
                        </th>
                        <StyledCell>
                            <div className={errors.name ? "error-wrapper" : ""}>
                                {errors.name && <span className="validation-error">{errors.name?.message || __("List name is required", "gc-lists")}</span>}
                                <input id="name" style={textWidth} type="text" {...register("name", { required: true })} />
                            </div>
                        </StyledCell>
                    </tr>
                    {user?.hasPhone && isNewList &&
                        <tr>
                            <th scope="row">
                                <label htmlFor="language">{__("Message type", "gc-lists")}</label>
                            </th>
                            <StyledCell>
                                <FieldError errors={[]} id="language">
                                    <div>
                                        <input {...register("language", { required: true })} type="radio" id="en" value="en" />
                                        <label htmlFor="en">{__('Email', 'gc-lists')}</label>
                                    </div>
                                    <div>
                                        <input {...register("language", { required: true })} type="radio" id="fr" value="fr" />
                                        <label htmlFor="fr">{__('Text message', 'gc-lists')}</label>
                                    </div>
                                    <p className="description" id="language-description">
                                        {__('Choose the type of message this list will receive.', 'gc-lists')}
                                    </p>
                                </FieldError>
                            </StyledCell>
                        </tr>
                    }
                </tbody>
            </table>

            <StyledDetails className="list-advanced">
                <summary>
                    <span>{__("Confirmation page settings", "gc-lists")}</span>
                </summary>

                <p>
                    {__("Subscribers will see the default confirmation pages if you’ve", "gc-lists")} <a href={__("https://articles.alpha.canada.ca/knowledge-base-de-connaissances/setting-up-a-gc-notify-integration/", "gc-lists")} target="_blank">
                        {__("set up a form", "gc-lists")}
                    </a> {__("to collect their email addresses.", "gc-lists")}
                </p>
                <p>{__("Changing these pages is optional.", "gc-lists")}</p>

                <table className="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label htmlFor="subscribe_redirect_url">{__("Subscribe confirmation", "gc-lists")}</label>
                            </th>
                            <StyledCell>
                                <div className={errors.name ? "error-wrapper" : ""}>
                                    {errors.subscribe_redirect_url && <span className="validation-error">{errors.subscribe_redirect_url?.message}</span>}
                                    <input id="subscribe_redirect_url" style={textWidth} type="text" {...register("subscribe_redirect_url")} />
                                    <p className="description" id="language-description">
                                        {__("The page people see after they subscribe.", "gc-lists")}
                                    </p>
                                </div>
                            </StyledCell>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label htmlFor="confirm_redirect_url">{__("Verify email confirmation", "gc-lists")}</label>
                            </th>
                            <StyledCell>
                                <div className={errors.confirm_redirect_url ? "error-wrapper" : ""}>
                                    {errors.confirm_redirect_url && <span className="validation-error">{errors.confirm_redirect_url?.message}</span>}
                                    <input id="confirm_redirect_url" style={textWidth} type="text" {...register("confirm_redirect_url")} />
                                    <p className="description" id="language-description">
                                        {__("The page people see after they verify their email address.", "gc-lists")}
                                    </p>
                                </div>
                            </StyledCell>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label htmlFor="unsubscribe_redirect_url">{__("Unsubscribe confirmation", "gc-lists")}</label>
                            </th>
                            <StyledCell>
                                <div className={errors.unsubscribe_redirect_url ? "error-wrapper" : ""}>
                                    {errors.unsubscribe_redirect_url && <span className="validation-error">{errors.unsubscribe_redirect_url?.message}</span>}
                                    <input id="unsubscribe_redirect_url" style={textWidth} type="text" {...register("unsubscribe_redirect_url")} />
                                    <p className="description" id="language-description">
                                        {__("The page people see after they unsubscribe.", "gc-lists")}
                                    </p>
                                </div>
                            </StyledCell>
                        </tr>
                    </tbody>
                </table>
            </StyledDetails>

            <div>
                <button style={{ marginRight: "20px" }} type="submit" className="button button-primary">
                    {saveButtonText}
                </button>

                <button
                    className="button"
                    type="button"
                    onClick={() => navigate('/lists/')}
                >
                    {__('Cancel', 'gc-lists')}
                </button>
            </div>

        </form>
    );
}
