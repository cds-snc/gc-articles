import { Importer, ImporterField } from "react-csv-importer";
import { CSVData } from "../types"

// theme CSS for React CSV Importer
import "react-csv-importer/dist/index.css";

export const UploadList = () => {
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

            const emails = rows.map((item) => {
                return item.email;
            });

            console.log(emails);

            // mock timeout to simulate processing
            await new Promise((resolve) => setTimeout(resolve, 500));
        }}
        onComplete={({ file, fields }: { file: File, fields: string[] }) => {
            // optional, invoked right after import is done (but user did not dismiss/reset the widget yet)
            console.log("finished import of file", file, "with fields", fields);
            // optional, invoked when import is done and user clicked "Finish"
            // (if this is not specified, the widget lets the user upload another file)
            console.log("importer dismissed");
        }}
    >
        <ImporterField name="email" label="Email" />
    </Importer >
}

export default UploadList;