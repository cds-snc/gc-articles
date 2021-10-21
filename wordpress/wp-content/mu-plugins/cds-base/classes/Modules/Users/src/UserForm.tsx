import * as React from 'react';
import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Button, Notice, Animate } from "@wordpress/components";

const useInput = initialValue => {
    const [value, setValue] = useState(initialValue);
    return {
        value,
        setValue,
        reset: () => setValue(""),
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

const tempErrors = {
    errors: ["The email address entered did not appear to be a valid email address. Please enter a valid email address."],
    location: "email",
    submittedValue: {value: ''}
}

const tempErrorsArray = [{ ...tempErrors },
    {
        errors: ['You have not selected a user role.'],
        location: "role",
        submittedValue: {value: ''}
    }
]

const ErrorSummary = ({ errors = [] }) => {
    return <Notice
        className="error-summary"
        status="error"
        isDismissible={false}
    >
        <h2>There is a problem</h2>
        <ul>   
            {errors.map((err) => 
                <li key={err.location}><a href={`#${err.location}`}>{err.errors[0]}</a></li>
            )}
        </ul>
    </Notice>
}

const FieldError = ({ errors = [], id = '', children }) => {
    const error = errors.find(err => err.location === id)

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
        // @todo handle server response
        console.log(response);
        
        setErrors(tempErrorsArray);
    }
    return (
        <div id="cds-react-form">
            {
                errors.length > 0 && <ErrorSummary errors={errors} />
            }

            <p>{__("Create a brand new user or if they already exists add them to this Collection.")}</p>

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
                                    <input type="text" id="email" aria-describedby="validation-error--email" {...bindEmail} />
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
                                <FieldError errors={errors} id={"role"}>
                                    <select disabled={isLoading ? true : false} name="role" id="role" aria-describedby="validation-error--role" defaultValue="gcadmin" {...bindRole} value={...role.value} >
                                        {roles.map((role) => {
                                            return <option value={role.id} disabled={role.disabled} selected={role.selected}>{role.name}</option>
                                        })}
                                    </select>
                                </FieldError>
                            </td>
                        </tr>
                        <Button
                            isPrimary
                            isBusy={isLoading}
                            disabled={isLoading}
                            type="submit" >
                            {__("Add user", "cds-snc")}
                        </Button>
                    </tbody>
                </table>
            </form>
        </div>
    );
}