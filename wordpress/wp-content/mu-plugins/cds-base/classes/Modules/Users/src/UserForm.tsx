import * as React from 'react';
import { useState, useEffect, useRef } from 'react';
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

import { getData, sendData } from 'util/fetch';
const Success = ({ message }) => {
    return <Notice
        status="success"
        isDismissible={false}
    >
        <h2>{__("Success!", "cds-snc")}</h2>
        <p>{message}</p>
    </Notice>
}

const ErrorSummary = ({ errors = [] }) => {
    return <Notice
        className="error-summary"
        status="error"
        isDismissible={false}
    >
        <h2>{__("There is a problem", "cds-snc")}</h2>
        <ul>
            {errors.map((err, i) => {
                return err.location ?
                    <li key={err.location}><a href={`#${err.location}`}>{err.message}</a></li> :
                    <li key={i}>{err.message}</li>
            })
            }
        </ul>
    </Notice>
}

const findErrorId = (errors = [], id = '') => errors.find(err => err.location === id)

const FieldError = ({ errors = [], id = '', children }) => {
    const error = findErrorId(errors, id)

    if (!id || !error) {
        return <div>{children}</div>
    }

    return <div className="error-wrapper">
        <span className="validation-error" id={`validation-error--${error.location}`}>
            {error.message}
        </span>
        {children}
    </div>
}

export const UserForm = (props) => {
    const emptyRole = { id: "", name: __("Select one", "cds-snc"), disabled: true, selected: true };
    const { value: email, bind: bindEmail, reset: resetEmail } = useInput('');
    const { value: role, bind: bindRole, reset: resetRole } = useInput({ value: '' });
    const [isLoading, setIsLoading] = useState(true);
    const [roles, setRoles] = useState([emptyRole]);
    const [roleDescription, setRoleDescription] = useState("");
    const [errors, setErrors] = useState([]);
    const [successMsg, setSuccessMsg] = useState('');
    const resetForm = () => {
        resetEmail('');
        resetRole({ value: '' });
        setErrors([]);
    }
    const errorSummary = useRef(null);

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

        try {
            const response = await sendData('users/v1/submit', { email, role });
            const [{ status }] = response;

            if (parseInt(status) >= 400) {
                const [{ errors: serverErrors = [] } = {}] = response;
                setErrors(serverErrors);
                setSuccessMsg(''); // clear success message
            } else if ([200, 201].includes(parseInt(status))) {
                const [{ message = '' } = {}] = response;
                resetForm(); // clear inputs and remove errors

                setSuccessMsg(message);
            }
        } catch (e) {
            setErrors([{ "errors": [{ "message": __("Internal server error", "cds-snc") }] }]);
            setSuccessMsg(''); // clear success message
        }
        errorSummary.current.focus();
    }

    return (
        <div className="cds-react-form">
            <div className="notice-container" role="alert" aria-atomic="true" tabIndex={-1} ref={errorSummary}>
                {
                    successMsg.length > 0 && <Success message={successMsg} />
                }
                {
                    errors.length > 0 && <ErrorSummary errors={errors} />
                }
            </div>

            <p>{__("Create a brand new user or add them to this Collection if they already exist.", "cds-snc")}</p>

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
                                    <select
                                        disabled={isLoading ? true : false}
                                        name="role" id="role"
                                        aria-describedby={findErrorId(errors, "role") ? `validation-error--role` : null}
                                        onChange={(event) => {
                                            const val = event.target.value;

                                            const role = roles.filter((role) => {
                                                return role.id === val
                                            })

                                            if (role.length >= 1) {
                                                setRoleDescription(role[0]["description"])
                                            } else {
                                                setRoleDescription("")
                                            }

                                            bindRole.onChange(event);
                                        }}
                                        value={...role.value}>
                                        {roles.map((role, i) => {
                                            return <option key={role.id || i} value={role.id} disabled={role.disabled}>{role.name}</option>
                                        })}
                                    </select>
                                </FieldError>
                                <p aria-live="polite" className="role-desc description">{roleDescription}</p>
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
