// @ts-nocheck
/**
 * External dependencies
 */
import * as React from 'react';

import { useCallback, useMemo } from "react";
import { Slate, Editable, withReact } from "slate-react";
import { createEditor } from "slate";
import { withHistory } from "slate-history";

import { TextWrapper } from "./Styles";
import { Leaf } from "./Leaf"
import useDecorateMarkup from "./useDecorateMarkup";

export const Editor = ({ template, handleChange, handleValidate }) => {
    const decorate = useDecorateMarkup();
    const renderLeaf = useCallback((props) => <Leaf {...props} />, []);
    const editor = useMemo(() => withHistory(withReact(createEditor())), []);
    return (
        <>
            <TextWrapper>
                <Slate editor={editor} value={template} onChange={value => {
                    handleChange(value)
                    handleValidate(value)
                }}>
                    <Editable
                        decorate={decorate}
                        renderLeaf={renderLeaf}
                        placeholder=""
                    />
                </Slate>
            </TextWrapper>
        </>
    );
};