/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { useForm } from "react-hook-form";
import { useNavigate } from 'react-router-dom';

 /**
  * Internal dependencies
  */
import { Back, StyledLink } from '../../common';
import { FieldError, NotAuthorized } from '../components';
import { useList } from '../../store';

export const ChooseMessage = () => {
    const navigate = useNavigate();
    const { register, handleSubmit, formState: { errors: formErrors } } = useForm();
    const fieldId = 'message_type';
    const { state: { user } } = useList();

    const onSubmit = (data: any) => {
        const { message_type } = data
        navigate(`/messages/edit/${message_type}/new`);
    }

    if (!user.hasPhone) {
        // Return "not authorized" message if user is not able to send phone messages
        return <NotAuthorized />
    }

    let errors = formErrors[fieldId] ? [{location: fieldId, message: __("Please select a message type", "gc-lists") }] : [];

    return (
        <>
            <StyledLink to={`/messages`}>
                <Back /> <span>{__("Back to messages ", "gc-lists")}</span>
            </StyledLink>
            <h1>{__("Create a new message", "gc-lists")}</h1>
             
            <form onSubmit={handleSubmit(onSubmit)}>
                <table className="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label htmlFor="message_type">{__('Choose message type', 'gc-lists')}</label>
                            </th>
                            <td>
                                <FieldError errors={errors} id={fieldId}>
                                    <div>
                                        <input {...register(fieldId, { required: true })} type="radio" id="message_type_email" value="email" />
                                        <label htmlFor="message_type_email" style={{display: 'inline-block', margin: '2px 0 5px 3px' }}>
                                            {__('Email', 'gc-lists')}
                                        </label>
                                    </div>
                                    <div>
                                        <input {...register(fieldId, { required: true })} type="radio" id="message_type_phone" value="phone" />
                                        <label htmlFor="message_type_phone" style={{display: 'inline-block', margin: '2px 0 5px 3px' }}>
                                            {__('Text message', 'gc-lists')}
                                        </label>
                                    </div>
                                </FieldError>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div style={{ marginTop: "10px" }}>
                    <button style={{ marginRight: "20px" }} type="submit" className="button button-primary">
                        {__('Create message', 'gc-lists')}
                    </button>
                    <button className="button" type="button" onClick={async () => { navigate(`/messages/`); }}>
                            {__('Cancel', 'gc-lists')}
                    </button>
                </div>
            </form>
        </>
    )
}

export default ChooseMessage;
