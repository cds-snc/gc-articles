// @ts-nocheck
/**
 * External dependencies
 */
import * as React from 'react';
import { __ } from "@wordpress/i18n";

export const findErrorId = (errors = [], id = '') => errors.find(err => err.location === id)

export const FieldError = ({ errors = [], id = '', children }) => {
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

export const ErrorSummary = ({ errors }) => {
    return (
        <div className="error-wrapper">
            <span className="validation-error">
                <h2>{__("There is a problem", "gc-lists")}</h2>
                <ul>
                    {errors.map((err, i) => {
                        return err.location ?
                            <li key={err.location}><a href={`#${err.location}`}>{err.message}</a></li> :
                            <li key={i}>{err.message}</li>
                    })
                    }
                </ul>
            </span>
        </div>
    )
}
