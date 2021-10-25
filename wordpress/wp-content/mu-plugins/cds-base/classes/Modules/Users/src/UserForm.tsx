import * as React from 'react';
import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Button, Notice } from "@wordpress/components";

const useInput = initialValue => {
    const [value, setValue] = useState(initialValue);
    return {
        value,
        setValue,
        reset: val => setValue(val),
        bind: {
            value,
            onChange: event => {
                setValue(event.target.value);
            }
        }
    };
};

import { getData } from '../../Notify/src/NotifyPanel';

// @todo move this out to a util function
const CDS_VARS = window.CDS_VARS || {};
const requestHeaders = new Headers({ 'Content-Type': 'application/json;charset=UTF-8' });
requestHeaders.append('X-WP-Nonce', CDS_VARS.rest_nonce);

export const sendData = async (endpoint: string, data) => {
    const response = await fetch(`${CDS_VARS.rest_url}${endpoint}`, {
        method: 'POST',
        headers: requestHeaders,
        mode: 'cors',
        cache: 'default',
        body: JSON.stringify(data)
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }
    return await response.json();
};

const Success = ({ message }) => {
    return <Notice
        status="success"
        isDismissible={false}
    >
        <h2>Success!</h2>
        <p>{message}</p>
    </Notice>
}

const ErrorSummary = ({ errors = [] }) => {
    return <Notice
        className="error-summary"
        status="error"
        isDismissible={false}
    >
        <h2>There is a problem</h2>
        <ul>
            {errors.map((err, i) => {
                return err.location ? 
                    <li key={err.location}><a href={`#${err.location}`}>{err.errors[0]}</a></li> :
                    <li key={i}>{err}</li>
                })
            }
        </ul>
    </Notice>
}

const findErrorId = (errors = [], id = '') => errors.find(err => err.location === id)

const FieldError = ({ errors = [], id = '', children }) => {
    const error = findErrorId(errors, id)

    if(!id || !error) {
        return <div>{children}</div>
    }

    return <div className="error-wrapper">
        <span className="validation-error" id={`validation-error--${error.location}`}>
            {/* Get the first error */}
            {error.errors[0]}
        </span>
        {children}
    </div>
}

export const UserForm = (props) => {
    const emptyRole = { id: "", name: __("Select one"), disabled: true, selected: true };
    const { value: email, bind: bindEmail, reset: resetEmail } = useInput('');
    const { value: role, bind: bindRole, reset: resetRole } = useInput({ value: '' });
    const [isLoading, setIsLoading] = useState(true);
    const [roles, setRoles] = useState([emptyRole]);
    const [errors, setErrors] = useState([]);
    const [successMsg, setSuccessMsg] = useState('');
    const resetForm = () => {
        resetEmail('');
        resetRole({value: ''});
        setErrors([]);
    }

    useEffect(() => {
        const getRoles = async () => {
            const response = await getData('users/v1/roles');

            if (response.length >= 1) {
                setRoles([emptyRole, ...response]);
            }

            setIsLoading(false);
        }

        getRoles();
    }, []);


    const handleSubmit = async (evt) => {
        evt.preventDefault();
        const response = await sendData('users/v1/submit', { email, role });

        const [ { status } ] = response;
        if(parseInt(status) >= 400) {
            const [ { errors: serverErrors = [] } = {} ] = response;
            setErrors(serverErrors);
            setSuccessMsg(''); // clear success message
            
        } else if (parseInt(status) == 201) {
            const [ { message = '' } = {} ] = response;
            resetForm(); // clear inputs and remove errors

            setSuccessMsg(message);
        }
    }

    return (
        <div className="cds-react-form">
            {
                successMsg.length > 0 && <Success message={successMsg}  />
            }
            {
                errors.length > 0 && <ErrorSummary errors={errors} />
            }

            <p>{__("Create a brand new user or add them to this Collection if they already exist.")}</p>

            <form onSubmit={handleSubmit} id="adduser">
                <table className="form-table">
                    <tbody>
                        <tr className="form-field form-required">
                            <th>
                                <label htmlFor="email">
                                    {__("Email", "cds-snc")}
                                </label>
                            </th>
                            <td>
                                {/* The "id" needs to match the field ID attribute */}
                                <FieldError errors={errors} id={"email"}>
                                    <input type="text" id="email" aria-describedby={findErrorId(errors, "email") ? `validation-error--email` : null} {...bindEmail} />
                                </FieldError>
                            </td>
                        </tr>
                        <tr className="form-field form-required">
                            <th>
                                <label>
                                    {__("Role", "cds-snc")}
                                </label>
                            </th>
                            <td>
                                {/* The "id" needs to match the field ID attribute */}
                                <FieldError errors={errors} id={"role"}>
                                    <select disabled={isLoading ? true : false} name="role" id="role" aria-describedby={findErrorId(errors, "role") ? `validation-error--role` : null} {...bindRole} value={...role.value}>
                                        {roles.map((role, i) => {
                                            return <option key={role.id || i} value={role.id} disabled={role.disabled}>{role.name}</option>
                                        })}
                                    </select>
                                </FieldError>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Button
                    isPrimary
                    isBusy={isLoading}
                    disabled={isLoading}
                    type="submit" >
                    {__("Add user", "cds-snc")}
                </Button>
            </form>
        </div>
    );
}
