import * as React from 'react';
import { useState } from 'react'
import { Importer, ImporterField } from "react-csv-importer";
import useFetch from 'use-http';
import { Navigate } from "react-router-dom";
import { Back } from "./Back";
import { ListType } from "../../types";
import { capitalize } from "../../util/functions";
import { useService } from '../../util/useService';

// theme CSS for React CSV Importer
import "react-csv-importer/dist/index.css";
import { useList } from '../../store/ListContext';

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
                            return item[uploadType];
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
                <Back />
            </div>
        </>)
}

export default UploadList;
