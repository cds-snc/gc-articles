import { useState, useCallback } from 'react';
import { Descendant } from "slate";

import { serialize, deserialize } from "./utils"

function useTemplateApi() {
    const [template, setTemplate] = useState<Descendant[]>(deserialize(localStorage.getItem('content') || "Your ((text)) here."))

    const saveTemplate = useCallback(
        (value: Descendant[]) => {
            if (!value) return;

            setTemplate(value);
            // Save the value to Local Storage.
            localStorage.setItem('content', serialize(value))
        },
        [],
    );

    return [template, saveTemplate];
}

export default useTemplateApi;