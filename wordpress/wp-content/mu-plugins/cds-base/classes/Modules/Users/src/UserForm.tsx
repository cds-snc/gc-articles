import * as React from 'react';
import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Button, Notice } from "@wordpress/components";

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
    const { value: email, bind: bindEmail, reset: resetEmail } = useInput('');
    const { value: role, bind: bindRole, reset: resetRole } = useInput({ value: "gcadmin" });
    const [isLoading, setIsLoading] = useState(true);
    const [errorMessage, setErrorMessage] = useState("Please enter an email address");
    // @todo 
    // - set these to useState + fetch the values from the server
    const roles = [
        { value: " ", label: __("Select One") },
        { value: "gcadmin", label: __("GC Admin1") },
        { value: "gceditor", label: __("GC Editor") }
    ];

    useEffect(() => {
        // @todo replace this
        const fetchData = async () => {
            const response = await getData('user/logins');
            setIsLoading(false);
            if (response.length >= 1) {
                //
            }
        };

        fetchData();
    }, []);


    const handleSubmit = (evt) => {
        evt.preventDefault();
        alert(`Email ${email} Role:${role}`);
        // call rest endpoint
        resetEmail();
        resetRole();
    }
    return (
        <div className="wrap">
            <h1 id="add-new-user">{__("Add user to collection")}</h1>
            <p>{__("Create a brand new user or if they already exists add them to this Collection.")}</p>

            {errorMessage && (
                <Notice
                    onRemove={() => setErrorMessage("")}
                    status="error"
                    isDismissible={true}
                >
                    {errorMessage}
                </Notice>
            )}

            <form onSubmit={handleSubmit} id="adduser">
                <table className="form-table">
                    <tbody>
                        <tr className="form-field form-required">
                            <th>
                                <label>
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
                                <select name="role" id="role" defaultValue="gcadmin" {...bindRole} value={...role.value} >
                                    {roles.map((role) => {
                                        return <option value={role.value}>{role.label}</option>
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