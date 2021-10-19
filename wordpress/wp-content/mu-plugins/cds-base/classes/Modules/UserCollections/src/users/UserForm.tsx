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
    const { value: firstName, bind: bindFirstName, reset: resetFirstName } = useInput('');
    const { value: lastName, bind: bindLastName, reset: resetLastName } = useInput('');

    const handleSubmit = (evt) => {
        evt.preventDefault();
        alert(`Submitting Name ${firstName} ${lastName}`);
        // call rest endpoint
        resetFirstName();
        resetLastName();
    }
    return (
        <div className="wrap">
            <h1 id="add-new-user">{__("Add User")}</h1>
            <form onSubmit={handleSubmit}>
                <table className="form-table">
                    <tbody>
                        <tr className="form-field form-required">
                            <th>
                                <label>
                                    {__("First Name:", "cds-snc")}
                                </label>
                            </th>
                            <td>
                                <input type="text" {...bindFirstName} />
                            </td>
                        </tr>
                        <tr className="form-field form-required">
                            <th>
                                <label>
                                    {__("Last Name:", "cds-snc")}
                                </label>
                            </th>
                            <td>
                                <input type="text" {...bindLastName} />
                            </td>
                        </tr>
                        <Button isPrimary
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