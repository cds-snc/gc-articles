/**
 * External dependencies
 */
import { useState } from 'react'
import { Importer, ImporterField } from "react-csv-importer";
import useFetch from 'use-http';
import { Navigate } from "react-router-dom";
import { __ } from "@wordpress/i18n";

// theme CSS for React CSV Importer
import "react-csv-importer/dist/index.css";

/**
 * Internal dependencies
 */
import { useService, useList } from '../../store';
import { ListType } from "../../types";
import { Back, StyledLink } from '../../common';
import { capitalize } from "../../util/functions";

export const UploadList = () => {
    const [finished, setFinished] = useState<boolean>(false)
    const { listId, type } = useService();
    let uploadType = '';

    switch (type) {
        case "email":
            uploadType = ListType.EMAIL;
            break;
        case "phone":
            uploadType = ListType.PHONE;
            break;
        default:
            throw new Error("Invalid field type");
    }

    const { state: { config: { listManagerApiPrefix } } } = useList();
    const { request, cache, response } = useFetch(listManagerApiPrefix, { data: [] })

    if (finished) {
        return <Navigate to={`/lists`} replace={true} />
    }

    return (
        <>
            <h3>{capitalize(type)} upload</h3>
            <Importer
                delimiter={" "}
                chunkSize={10000} // optional, internal parsing chunk size in bytes
                assumeNoHeaders={false} // optional, keeps "data has headers" checkbox off by default
                restartable={false} // optional, lets user choose to upload another file when import is complete
                onStart={({ file, fields }) => {
                    // optional, invoked when user has mapped columns and started import
                    console.log("starting import of file", file, "with fields", fields);
                }}
                processChunk={async (rows) => {
                    return new Promise(async (resolve) => {
                        const data = rows.map((item) => {
                            // @ts-ignore
                            return item[uploadType].replace(/(^,)|(,$)/g, '');
                        });

                        const payload = { [uploadType]: data };
                        await request.post(`/list/${listId}/import`, payload)

                        if (response.ok) {
                            cache.clear();
                            console.log(response.data);
                            resolve()
                        }
                    });
                }}
                onComplete={({ file, fields }) => {
                    // optional, invoked right after import is done (but user did not dismiss/reset the widget yet)
                    console.log("finished import of file", file, "with fields", fields);
                }}
                onClose={() => {
                    // optional, invoked when import is done and user clicked "Finish"
                    // (if this is not specified, the widget lets the user upload another file)
                    console.log("importer dismissed");
                    setFinished(true);
                }}
            >
                {uploadType === ListType.EMAIL && <ImporterField name="email" label="Email" />}
                {uploadType === ListType.PHONE && <ImporterField name="phone" label="Phone" />}
            </Importer >
            <div style={{ marginTop: "0.5rem" }}>
                <StyledLink to={`/lists`}>
                    <Back /> <span>{__("Go back", "gc-lists")}</span>
                </StyledLink>
                <Back />
            </div>
        </>)
}

export default UploadList;
