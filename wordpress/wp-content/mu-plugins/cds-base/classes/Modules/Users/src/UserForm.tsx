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

export const UserForm = (props) => {
    const emptyRole = { id: " ", name: __("Select One") };
    const { value: email, bind: bindEmail, reset: resetEmail } = useInput('');
    const { value: role, bind: bindRole, reset: resetRole } = useInput({ value: '' });
    const [isLoading, setIsLoading] = useState(true);
    const [roles, setRoles] = useState([emptyRole]);
    const [errorMessage, setErrorMessage] = useState("Please enter an email address");

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


    const handleSubmit = (evt) => {
        evt.preventDefault();
        alert(`Email ${email} Role:${role}`);
        // call rest endpoint
        resetEmail();
        resetRole();
    }
    return (
        <div className="wrap" id="cds-react-form">
            <h1 id="add-new-user">{__("Add user to collection")}</h1>
            <p>{__("Create a brand new user or if they already exists add them to this Collection.")}</p>
            {errorMessage && (
                <Animate type="appear" options={{ origin: "top left" }}>
                    {({ className }) => (
                        <Notice
                            className={className}
                            onRemove={() => setErrorMessage("")}
                            status="error"
                            isDismissible={true}
                        >
                            {errorMessage}
                        </Notice>
                    )}
                </Animate>
            )}

            <form onSubmit={handleSubmit} id="adduser">
                <table className="form-table">
                    <tbody>
                        <tr className="form-field form-required">
                            <th>
                                <label className="error">
                                    {__("Email:", "cds-snc")}
                                </label>
                            </th>
                            <td>
                                <input type="text" {...bindEmail} />
                            </td>
                        </tr>
                        <tr className="form-field form-required">
                            <th>
                                <label>
                                    {__("Role:", "cds-snc")}
                                </label>
                            </th>
                            <td>
                                <select disabled={isLoading ? true : false} name="role" id="role" defaultValue="gcadmin" {...bindRole} value={...role.value} >
                                    {roles.map((role) => {
                                        return <option value={role.id}>{role.name}</option>
                                    })}
                                </select>
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