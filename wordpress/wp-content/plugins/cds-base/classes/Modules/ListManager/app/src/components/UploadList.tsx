import * as React from 'react';
import { useState } from 'react'
import { Importer, ImporterField } from "react-csv-importer";
import useFetch from 'use-http';
import { useParams, Navigate } from "react-router-dom";

// theme CSS for React CSV Importer
import "react-csv-importer/dist/index.css";

export const UploadList = () => {
    const [finished, setFinished] = useState<boolean>(false)
    const { request, cache, response } = useFetch({ data: [] })
    const params = useParams();
    const serviceId = params?.serviceId;
    const listId = params?.listId;

    if (finished) {
        return <Navigate to={`/service/${serviceId}`} replace={true} />
    }

    return <Importer
        chunkSize={10000} // optional, internal parsing chunk size in bytes
        assumeNoHeaders={false} // optional, keeps "data has headers" checkbox off by default
        restartable={false} // optional, lets user choose to upload another file when import is complete
        onStart={({ file, fields }) => {
            // optional, invoked when user has mapped columns and started import
            console.log("starting import of file", file, "with fields", fields);
        }}
        processChunk={async (rows) => {
            // required, receives a list of parsed objects based on defined fields and user column mapping;
            // may be called several times if file is large
            // (if this callback returns a promise, the widget will wait for it before parsing more data)
            // console.log("received batch of rows", rows);

            // await new Promise((resolve) => setTimeout(resolve, 500));

            return new Promise(async (resolve) => {

                const emails = rows.map((item) => {
                    return item.email;
                });

                await request.post('/listimport', { list_id: listId, emails })

                if (response.ok) {
                    cache.clear();
                    console.log(await response.json());
                    resolve()
                }
            });
            // mock timeout to simulate processing
            // await new Promise((resolve) => setTimeout(resolve, 500));
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
        <ImporterField name="email" label="Email" />
    </Importer >
}

export default UploadList;