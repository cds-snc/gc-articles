// @ts-nocheck
import { useState } from 'react';

export const useInput = initialValue => {
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