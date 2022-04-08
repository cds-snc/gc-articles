// @ts-nocheck
import React, { useCallback, useMemo, useState } from "react";
import { Slate, Editable, withReact } from "slate-react";
import { Text, createEditor, Node, Descendant } from "slate";
import { withHistory } from "slate-history";
import { TextWrapper, StyledSpan, Label, Hint } from "./editor/Styles";
import Prism from "prismjs";
// eslint-disable-next-line
Prism.languages.markdown = Prism.languages.extend("markup", {}), Prism.languages.insertBefore("markdown", "prolog", { blockquote: { pattern: /^>(?:[\t ]*>)*/m, alias: "punctuation" }, code: [{ pattern: /^(?: {4}|\t).+/m, alias: "keyword" }, { pattern: /``.+?``|`[^`\n]+`/, alias: "keyword" }], title: [{ pattern: /\w+.*(?:\r?\n|\r)(?:==+|--+)/, alias: "important", inside: { punctuation: /==+$|--+$/ } }, { pattern: /(^\s*)#+.+/m, lookbehind: !0, alias: "important", inside: { punctuation: /^#+|#+$/ } }], hr: { pattern: /(^\s*)([*-])([\t ]*\2){2,}(?=\s*$)/m, lookbehind: !0, alias: "punctuation" }, list: { pattern: /(^\s*)(?:[*+-]|\d+\.)(?=[\t ].)/m, lookbehind: !0, alias: "punctuation" }, "tag": { pattern: /\(\(([^\)\((\?)]+)(\?\?)?([^\)\(]*)\)\)/g }, "url-reference": { pattern: /!?\[[^\]]+\]:[\t ]+(?:\S+|<(?:\\.|[^>\\])+>)(?:[\t ]+(?:"(?:\\.|[^"\\])*"|'(?:\\.|[^'\\])*'|\((?:\\.|[^)\\])*\)))?/, inside: { variable: { pattern: /^(!?\[)[^\]]+/, lookbehind: !0 }, string: /(?:"(?:\\.|[^"\\])*"|'(?:\\.|[^'\\])*'|\((?:\\.|[^)\\])*\))$/, punctuation: /^[\[\]!:]|[<>]/ }, alias: "url" }, bold: { pattern: /(^|[^\\])(\*\*|__)(?:(?:\r?\n|\r)(?!\r?\n|\r)|.)+?\2/, lookbehind: !0, inside: { punctuation: /^\*\*|^__|\*\*$|__$/ } }, italic: { pattern: /(^|[^\\])([*_])(?:(?:\r?\n|\r)(?!\r?\n|\r)|.)+?\2/, lookbehind: !0, inside: { punctuation: /^[*_]|[*_]$/ } }, url: { pattern: /!?\[[^\]]+\](?:\([^\s)]+(?:[\t ]+"(?:\\.|[^"\\])*")?\)| ?\[[^\]\n]*\])/, inside: { variable: { pattern: /(!?\[)[^\]]+(?=\]$)/, lookbehind: !0 }, string: { pattern: /"(?:\\.|[^"\\])*"(?=\)$)/ } } } }), Prism.languages.markdown.bold.inside.url = Prism.util.clone(Prism.languages.markdown.url), Prism.languages.markdown.italic.inside.url = Prism.util.clone(Prism.languages.markdown.url), Prism.languages.markdown.bold.inside.italic = Prism.util.clone(Prism.languages.markdown.italic), Prism.languages.markdown.italic.inside.bold = Prism.util.clone(Prism.languages.markdown.bold); // prettier-ignore

// Define a serializing function that takes a value and returns a string.
const serialize = (value: Descendant[]) => {
    return (
        value!
            // Return the string content of each paragraph in the value's children.
            .map(n => Node.string(n))
            // Join them all with line breaks denoting paragraphs.
            .join('\n')
    )
}

// Define a deserializing function that takes a string and returns a value.
const deserialize = (string: string) => {
    // Return a value array of children derived by splitting the string.
    return string.split('\n').map(line => {
        return {
            children: [{ text: line }],
        }
    })
}

export const Editor = ({ onSend }: { onSend: (data: string) => void }) => {
    // Use our deserializing function to read the data from Local Storage.

    const [value, setValue] = useState<Descendant[]>(deserialize(localStorage.getItem('content') || "Your ((text)) here."))

    const sendValues = useCallback(
        () => {

            if (!value) return;
            onSend(serialize(value))
        },
        [value, onSend],
    );

    const renderLeaf = useCallback((props) => <Leaf {...props} />, []);
    // @ts-ignore
    const editor = useMemo(() => withHistory(withReact(createEditor())), []);
    const decorate = useCallback(([node, path]) => {
        const ranges: [] = [];

        if (!Text.isText(node)) {
            return ranges;
        }

        // @ts-ignore
        const getLength = (token) => {
            if (typeof token === "string") {
                return token.length;
            } else if (typeof token.content === "string") {
                return token.content.length;
            } else {
                // @ts-ignore
                return token.content.reduce((l, t) => l + getLength(t), 0);
            }
        };

        const tokens = Prism.tokenize(node.text, Prism.languages.markdown);
        let start = 0;

        for (const token of tokens) {
            const length = getLength(token);
            const end = start + length;

            if (typeof token !== "string") {
                ranges.push({
                    [token.type]: true,
                    // @ts-ignore
                    anchor: { path, offset: start },
                    // @ts-ignore
                    focus: { path, offset: end }
                });
            }

            start = end;
        }

        return ranges;
    }, []);

    return (
        <>
            <Label htmlFor="template_content">Message</Label>
            <Hint>Use the email <a href="https://notification.canada.ca/format">formatting guide</a> (Opens in a new tab) to craft your message.</Hint>
            <TextWrapper>
                <Slate editor={editor} value={value} onChange={value => {
                    setValue(value)
                    // Save the value to Local Storage.
                    localStorage.setItem('content', serialize(value))
                }}>
                    <Editable
                        decorate={decorate}
                        renderLeaf={renderLeaf}
                        placeholder="Write some markdown..."
                    />
                </Slate>


            </TextWrapper>

            <button className="button" onClick={sendValues}>Send Email</button>
        </>
    );
};

// @ts-ignore
const Leaf = ({ attributes, children, leaf }) => {
    return (
        <StyledSpan {...attributes} leaf={leaf}>
            {children}
        </StyledSpan >
    );
};