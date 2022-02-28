import * as React from 'react';
import styled from 'styled-components';
import { useTable } from 'react-table';
import { Link } from "react-router-dom";

import { List } from "../types"
import { useList } from "../store/ListContext";
import { Spinner } from './Spinner';
import { DeleteActionLink } from './DeleteActionLink';
import { ResetActionLink } from './ResetActionLink';
import { Messages } from "./Messages"
import { useListFetch } from '../store/UseListFetch';

const TemplateGroupStyles = styled.div`
  margin: 1rem;
`

const DetailsLinkStyles = styled.div`
    margin: .5rem 0;
`

const HeaderStyles = styled.div`
    display: flex;
    justify-content: space-between;
`

const Table = ({ columns, data }: { columns: any, data: List[] }) => {
    const {
        getTableProps,
        getTableBodyProps,
        headerGroups,
        rows,
        prepareRow,
    } = useTable({
        columns,
        data,
    })

    return (
        <table {...getTableProps()} className="wp-list-table widefat fixed striped table-view-list users">
            <thead>
                {headerGroups.map(headerGroup => (
                    <tr {...headerGroup.getHeaderGroupProps()}>
                        {headerGroup.headers.map(column => (
                            <th {...column.getHeaderProps()}>{column.render('Header')}</th>
                        ))}
                    </tr>
                ))}
            </thead>
            <tbody {...getTableBodyProps()}>
                {rows.map((row, i) => {
                    prepareRow(row)
                    return (
                        <tr {...row.getRowProps()}>
                            {row.cells.map(cell => {
                                return <td {...cell.getCellProps()}>{cell.render('Cell')}</td>
                            })}
                        </tr>
                    )
                })}
            </tbody>
        </table>
    )
}

const CreateListLink = () => {
    return <Link className="button button-primary" to={{ pathname: `list/create` }}>Create new list</Link>
}

const UploadListLink = ({ id }: { id: string }) => {
    return <Link className="button action" to={{ pathname: `/upload/${id}` }}>Upload List</Link>
}

const NOTIFY_UTL = "https://notification.canada.ca";

const templateLink = (serviceId: string, templateId: string) => {
    return `${NOTIFY_UTL}/services/${serviceId}/templates/${templateId}`;
}

export const ListViewTable = () => {
    const { state } = useList();
    const { lists, loading } = state;

    useListFetch();

    const columns = React.useMemo(
        () => [
            {
                Header: () => { return <HeaderStyles><CreateListLink /></HeaderStyles> },
                accessor: 'lists',
                columns: [
                    {
                        Header: 'Name',
                        accessor: 'name',
                        Cell: ({ row }: { row: any }) => {
                            return (
                                <strong>
                                    <Link
                                        className="row-title"
                                        to={{
                                            pathname: `list/${row?.values?.id}`,
                                        }}
                                    >
                                        {row?.values?.name}
                                    </Link>
                                </strong>
                            )
                        },
                    },

                    {
                        Header: 'List Id',
                        accessor: 'id',
                    },

                    {
                        Header: 'Language',
                        accessor: 'language',
                    },
                    {
                        Header: 'Service Id',
                        accessor: 'service_id',
                        Cell: ({ row }: { row: any }) => {
                            return <a href={`${NOTIFY_UTL}/services/${row?.values?.service_id}`}>{row?.values?.service_id}</a>
                        },
                    },

                    {
                        Header: 'Templates',
                        accessor: 'subscribe_email_template_id',
                        Cell: ({ row }: { row: any }) => {

                            const values = row?.original;
                            return (
                                <details>
                                    <summary>Details</summary>
                                    <TemplateGroupStyles>
                                        <div><strong>Email</strong></div>
                                        <DetailsLinkStyles><a href={templateLink(values.serviceId, values.subscribe_email_template_id)}>Subscribe</a></DetailsLinkStyles>
                                        <DetailsLinkStyles><a href={templateLink(values.serviceId, values.unsubscribe_email_template_id)}>Unsubscribe</a></DetailsLinkStyles>
                                    </TemplateGroupStyles>

                                    <TemplateGroupStyles>
                                        <div><strong>Phone</strong></div>
                                        <DetailsLinkStyles><a href={templateLink(values.serviceId, values.subscribe_phone_template_id)}>Subscribe</a></DetailsLinkStyles>
                                        <DetailsLinkStyles><a href={templateLink(values.serviceId, values.unsubscribe_phone_template_id)}>Unsubscribe</a></DetailsLinkStyles>
                                    </TemplateGroupStyles>

                                    <TemplateGroupStyles>
                                        <div><strong>Confirm Url</strong></div>
                                        <DetailsLinkStyles><a href={values.confirm_redirect_url}>Confirm</a></DetailsLinkStyles>
                                    </TemplateGroupStyles>
                                </details>)

                        },
                    },
                    {
                        Header: 'Subscribers',
                        accessor: 'subscriber_count',
                    },
                    {
                        Header: 'Delete',
                        accessor: 'delete',
                        Cell: ({ row }: { row: any }) => {
                            return (<DeleteActionLink id={`${row?.values?.id}`} />)
                        },
                    },
                    {
                        Header: 'Reset',
                        accessor: 'reset',
                        Cell: ({ row }: { row: any }) => {
                            return (<ResetActionLink id={`${row?.values?.id}`} />);
                        },
                    },

                    {
                        Header: 'Upload',
                        accessor: 'active',
                        Cell: ({ row }: { row: any }) => {
                            return <UploadListLink id={`${row?.values?.id}`} />
                        },
                    },
                ],
            },
        ],
        []);

    if (loading) {
        return <Spinner />
    }

    return (
        <><Messages /><Table columns={columns} data={lists} /></>
    )
}