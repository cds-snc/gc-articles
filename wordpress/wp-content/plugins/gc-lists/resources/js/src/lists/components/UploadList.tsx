/**
 * External dependencies
 */
import styled from 'styled-components';
import { useState } from 'react'
import { Importer, ImporterField } from "react-csv-importer";
import useFetch from 'use-http';
import { Navigate, useNavigate } from "react-router-dom";
import { __ } from "@wordpress/i18n";

// theme CSS for React CSV Importer
import "react-csv-importer/dist/index.css";

/**
 * Internal dependencies
 */
import { useService, useList } from '../../store';
import { ListType } from "../../types";
import { Back, StyledLink } from '../../common';

const StyledDivider  = styled.div`
    margin: 20px 10px;

    /* Give "importer" buttons 'button-primary' styles since they are the main actions */
    .CSVImporter_TextButton {
        background: #26374a;
        border-color: #26374a;
        color: #fff;
    }

    .CSVImporter_TextButton:active {
        background-color: #16446c;
        border-color: #000;
    }

    .CSVImporter_TextButton:hover,
    .CSVImporter_TextButton:focus {
        background-color: #1c578a;
        border-color: #091c2d;
    }


    .CSVImporter_TextButton:focus {
        box-shadow: 0 0 0 1px #fff, 0 0 0 3px #26374a;
        outline: 2px solid transparent;
        outline-offset: 0;
    }
`

export const UploadList = () => {
    const navigate = useNavigate();
    const [finished, setFinished] = useState<boolean>(false)
    const { listId, type } = useService();
    const titleType = type === ListType.PHONE ? 'text message' : 'email';

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
            <StyledLink to={`/lists/${listId}/update`}>
                <Back /> <span>{__("Edit list", "gc-lists")}</span>
            </StyledLink>
            <h1>Import {titleType} subscribers</h1>
            <StyledDivider>
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
            </StyledDivider>
            <button
                className="button"
                type="button"
                onClick={() => navigate('/lists/')}
            >
                {__('Back to mailing lists', 'gc-lists')}
            </button>
        </>)
}

export default UploadList;
