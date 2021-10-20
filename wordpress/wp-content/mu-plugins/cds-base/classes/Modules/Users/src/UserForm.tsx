import * as React from 'react';
import { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { Button } from "@wordpress/components";

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

export const UserForm = (props) => {
    const { value: email, bind: bindEmail, reset: resetEmail } = useInput('');
    const { value: role, bind: bindRole, reset: resetRole } = useInput('');

    // @todo 
    // - set these to useState + fetch the values from the server
    // - Time bind the values for the select
    const roles = [
        { value: " ", label: __("Select One") },
        { value: "gcadmin", label: __("GC Admin1") },
        { value: "gceditor", label: __("GC Editor") }
    ]

    const handleSubmit = (evt) => {
        evt.preventDefault();
        alert(`Email ${email} ${role}`);
        // call rest endpoint
        resetEmail();
        resetRole();
    }
    return (
        <div className="wrap">
            <h1 id="add-new-user">{__("Add user to collection")}</h1>
            <p>{__("Create a brand new user or if they already exists add them to this Collection.")}</p>
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
                                <select name="role" id="role">
                                    {roles.map((role) => {
                                        return <option value={role.value}>{role.label}</option>
                                    })}
                                </select>
                            </td>
                        </tr>
                        <Button
                            isPrimary
                            className="button-primary"
                            type="submit" >
                            {__("Add user", "cds-snc")}
                        </Button>
                    </tbody>
                </table>
            </form>
        </div>
    );
}