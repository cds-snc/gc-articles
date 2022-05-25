// @ts-nocheck
import * as React from 'react';
import { useState, useEffect } from 'react';
import { Table } from "./Table";
import { __ } from "@wordpress/i18n";
import useFetch from 'use-http';
import { v4 as uuidv4 } from "uuid";
// import useFetch from 'use-http';

export const SendingHistory = ({ perPage, pageNav }: { perPage?: number, pageNav?: boolean }) => {
    const [data, setData] = useState([]);
    const { request, response } = useFetch({ data: [] })

    useEffect(() => {
        const getSentMessages = async () => {
            await request.get(`/messages/sent?c=${uuidv4()}`);
            if (response.ok) {
                setData(await response.json());
            }
        }
        getSentMessages();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);


    const columns = React.useMemo(
        () => [

            {
                Header: __('Message name', "cds-snc"),
                accessor: 'name',
            },
            {
                Header: __('Date sent', "cds-snc"),
                accessor: 'created_at',
            },
            {
                Header: __('List name', "cds-snc"),
                accessor: 'sent_to_list_name',
            },
            {
                Header: __('Sender', "cds-snc"),
                accessor: 'sent_by_email',
            },
        ],
        []
    )

    return (
        <>
            <h2>{__("Sending History", "cds-snc")}</h2>
            <Table columns={columns} data={data} perPage={perPage} pageNav={pageNav} />
        </>
    )
}