import * as React from 'react';
import { Table } from "./Table";
import { __ } from "@wordpress/i18n";

export const SendingHistory = () => {

    const columns = React.useMemo(
        () => [

            {
                Header: __('Message type', "cds-snc"),
                accessor: 'type',
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
            <Table columns={columns} data={[]} />
        </>
    )
}