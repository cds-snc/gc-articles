// @ts-nocheck
import * as React from 'react';
import { useState, useEffect } from 'react';
import { Table } from "./Table";
import { __ } from "@wordpress/i18n";
// import useFetch from 'use-http';

export const SendingHistory = () => {
    // const { request, response } = useFetch({ data: [] });
    const [data, setData] = useState([]);


    useEffect(() => {
        const getSentMessages = async () => {
            // await request.get(`/mesasages/`);
            const response = { ok: true }
            if (response.ok) {
                setData([
                    { name: "GC news feed - April 2022 (FR)", date: "2022/04/20 at 7:56 pm", list: "Newsletter monthly sendout (125 subscribers)", sender: "gcadmin@cds-snc.ca" },
                    { name: "GC news feed - Mar 2022 (FR)", date: "2022/03/20 at 7:56 pm", list: "Newsletter monthly sendout (125 subscribers)", sender: "gcadmin@cds-snc.ca" },
                    { name: "GC news feed - Feb 2022 (FR)", date: "2022/02/20 at 7:56 pm", list: "Newsletter monthly sendout (125 subscribers)", sender: "gcadmin@cds-snc.ca" },
                    { name: "GC news feed - Jan 2022 (FR)", date: "2022/01/20 at 7:56 pm", list: "Newsletter monthly sendout (125 subscribers)", sender: "gcadmin@cds-snc.ca" },
                    { name: "All about Hot Dogs (EN)", date: "2022/02/20 at 7:56 pm", list: "Newsletter monthly sendout (6.1 million subscribers)", sender: "gcadmin@cds-snc.ca" },
                    { name: "Top Hot Dog vendors (EN)", date: "2022/01/20 at 7:56 pm", list: "Newsletter monthly sendout (6 million subscribers", sender: "gcadmin@cds-snc.ca" }

                ]);

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
                accessor: 'date',
            },
            {
                Header: __('List name', "cds-snc"),
                accessor: 'list',
            },
            {
                Header: __('Sender', "cds-snc"),
                accessor: 'sender',
            },
        ],
        []
    )

    return (
        <>
            <h2>{__("Sending History", "cds-snc")}</h2>
            <Table columns={columns} data={data} />
        </>
    )
}