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

    const handleSubmit = (evt) => {
        evt.preventDefault();
        alert(`Finding user by email ${email}`);
        // call rest endpoint
        resetEmail();
    }
    return (
        <form onSubmit={handleSubmit}>
            <table className="form-table">
                <tbody>
                    <tr className="form-field form-required">
                        <th>
                            <label>
                                {__("Email address", "cds-snc")}
                            </label>
                        </th>
                        <td>
                            <input type="text" {...bindEmail} />
                        </td>
                    </tr>
                    <Button isPrimary
                        className="button-primary"
                        type="submit" >
                        {__("Find user", "cds-snc")}
                    </Button>
                </tbody>
            </table>
        </form>
    );
}