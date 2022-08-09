/**
 * External dependencies
 */
import { useEffect } from "react";
import { useForm } from "react-hook-form";
import { __ } from "@wordpress/i18n";
import styled from 'styled-components';

/**
 * Internal dependencies
 */
import { List, FieldError as ErrorType } from "../../types";
import { useList } from "../../store";

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
        <form style={{ maxWidth: '700px' }} onSubmit={handleSubmit(handler)}>
            <table className="form-table" role="presentation">

                {/* @todo np phone access use default language field will be replaced with "list_type" field */}
                {!user?.hasPhone && <input type="hidden" name="language" value="en" />}
                <input id="service_id" type="hidden" {...register("service_id", { required: true })} />

                {/* Optional hidden field, pulled from value entered on the settings panel if set */}
                <input id="unsubscribe_email_template_id" type="hidden" {...register("unsubscribe_email_template_id")} />

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
                    {user?.hasPhone &&
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
                                        Choose the type of message this list will get.
                                    </p>
                                </FieldError>
                            </StyledCell>
                        </tr>
                    }
                </tbody>
            </table>

            <StyledDetails className="list-advanced">
                <summary>
                    <span>{__("Advanced list settings", "gc-lists")}</span>
                </summary>
                <p>{__("These settings are optional.", "gc-lists")} {__("They only apply if you setup a form to collect emails from subscribers.", "gc-lists")}</p>

                <table className="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label htmlFor="subscribe_redirect_url">{__("Subscribe confirmation url", "gc-lists")}</label>
                            </th>
                            <StyledCell>
                                <div className={errors.name ? "error-wrapper" : ""}>
                                    {errors.subscribe_redirect_url && <span className="validation-error">{errors.subscribe_redirect_url?.message}</span>}
                                    <input id="subscribe_redirect_url" style={textWidth} type="text" {...register("subscribe_redirect_url")} />
                                    <p className="description" id="language-description">
                                        {__("Subscribers are directed to this page when submitting the subscribe request form.", "gc-lists")}
                                    </p>
                                </div>
                            </StyledCell>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label htmlFor="unsubscribe_redirect_url">{__("Unsubscribe redirect url", "gc-lists")}</label>
                            </th>
                            <StyledCell>
                                <div className={errors.unsubscribe_redirect_url ? "error-wrapper" : ""}>
                                    {errors.unsubscribe_redirect_url && <span className="validation-error">{errors.unsubscribe_redirect_url?.message}</span>}
                                    <input id="unsubscribe_redirect_url" style={textWidth} type="text" {...register("unsubscribe_redirect_url")} />
                                    <p className="description" id="language-description">
                                        {__("Subscribers are directed to this page when submitting the unsubscribe request form.", "gc-lists")}
                                    </p>
                                </div>
                            </StyledCell>
                        </tr>

                        <tr>
                            <th scope="row">
                                <label htmlFor="confirm_redirect_url">{__("Verify email url", "gc-lists")}</label>
                            </th>
                            <StyledCell>
                                <div className={errors.confirm_redirect_url ? "error-wrapper" : ""}>
                                    {errors.confirm_redirect_url && <span className="validation-error">{errors.confirm_redirect_url?.message}</span>}
                                    <input id="confirm_redirect_url" style={textWidth} type="text" {...register("confirm_redirect_url")} />
                                    <p className="description" id="language-description">
                                        {__("Subscribers are directed to this page when they confirm their subscription to a list.", "gc-lists")}
                                    </p>
                                </div>
                            </StyledCell>
                        </tr>
                    </tbody>
                </table>
            </StyledDetails>

            <div>
                <button style={{ marginRight: "20px" }} type="submit" className="button button-primary">
                    {__('Save and continue', 'gc-lists')}
                </button>

                <button
                    className="button"
                    type="button"
                    onClick={() => {
                        console.log('cancel!!');
                    }}
                >
                    {__('Cancel', 'gc-lists')}
                </button>
            </div>

        </form>
    );
}
