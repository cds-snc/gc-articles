// @ts-nocheck
import React, { useCallback, useMemo } from "react";
import { Slate, Editable, withReact } from "slate-react";
import { createEditor } from "slate";
import { withHistory } from "slate-history";

import { TextWrapper, Label, Hint } from "./Styles";
import { Leaf } from "./Leaf"
import useTemplateApi from "./useTemplateApi";
import useDecorateMarkup from "./useDecorateMarkup";

export const Editor = () => {
    const [template, saveTemplate] = useTemplateApi();
    const decorate = useDecorateMarkup();
    const renderLeaf = useCallback((props) => <Leaf {...props} />, []);
    const editor = useMemo(() => withHistory(withReact(createEditor())), []);
    return (
        <>
            <Label htmlFor="template_content">Message</Label>
            <Hint>Use the email <a href="https://notification.canada.ca/format">formatting guide</a> (Opens in a new tab) to craft your message.</Hint>
            <TextWrapper>
                <Slate editor={editor} value={template} onChange={value => {
                    saveTemplate(value)
                }}>
                    <Editable
                        decorate={decorate}
                        renderLeaf={renderLeaf}
                        placeholder="Write some markdown..."
                    />
                </Slate>
            </TextWrapper>
        </>
    );
};