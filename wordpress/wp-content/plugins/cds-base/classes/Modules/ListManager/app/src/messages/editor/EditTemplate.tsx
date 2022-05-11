import * as React from 'react';
import { Editor } from "../editor/Editor";

export const EditTemplate = () => {
    // fetch template
    return (
        <>
            <div>
                <strong>Message name</strong>
                <p>Your recipients will not see this message name.</p>
                <input type="text" id="name" name="name"></input>
            </div>

            <div>
                <strong>Subject line of the email</strong>
                <p>Tell recipients what the message is about. Try to keep it shorter than 10 words.</p>
                <input type="text" id="subject" name="subject"></input>
            </div>

            <Editor />
            <button className="button" onClick={() => { }}>Send message to list</button>
            <button className="button" onClick={() => { }}>Save template</button>
            {/* <a href="#">Delete this message template</a> */}
        </>
    )
}

export default EditTemplate;