import React from 'react';
import styled from 'styled-components';
import { useTable } from 'react-table';
import { Link } from "react-router-dom";

import { List } from "../types"
import { useList } from "../store/ListContext";
import { Spinner } from './Spinner';
import { DeleteActionLink } from './DeleteActionLink';
import { ResetActionLink } from './ResetActionLink';
import { Messages } from "./Messages"


const TableStyles = styled.div`
  padding: 1rem;

  table {
    border-spacing: 0;
    border: 1px solid black;

    tr {
      :last-child {
        td {
          border-bottom: 0;
        }
      }
    }

    th,
    td {
      margin: 0;
      padding: 0.5rem;
      border-bottom: 1px solid black;
      border-right: 1px solid black;

      :last-child {
        border-right: 0;
      }
    }
  }
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
        <table {...getTableProps()}>
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
    return <Link to={{ pathname: `list/create` }}>Create new list</Link>
}

const NOTIFY_UTL = "https://notification.canada.ca";

const templateLink = (serviceId: string, templateId: string) => {
    return `${NOTIFY_UTL}/services/${serviceId}/templates/${templateId}`;
}

export const ListViewTable = () => {
    const { state, loading } = useList();
    const { lists } = state;

    const columns = React.useMemo(
        () => [
            {
                Header: () => { return <HeaderStyles><div>Lists</div><CreateListLink /></HeaderStyles> },
                accessor: 'lists',
                columns: [
                    {
                        Header: 'Name',
                        accessor: 'name',
                        Cell: ({ row }: { row: any }) => {
                            return (
                                <Link
                                    to={{
                                        pathname: `list/${row?.values?.id}`,
                                    }}
                                >
                                    {row?.values?.name}
                                </Link>
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
                        Header: 'Subscribe Email Template Id',
                        accessor: 'subscribe_email_template_id',
                        Cell: ({ row }: { row: any }) => {
                            const serviceId = row?.values?.service_id;
                            const templateId = row?.values?.subscribe_email_template_id;
                            return <a href={templateLink(serviceId, templateId)}>{templateId}</a>
                        },
                    },

                    {
                        Header: 'Unsubscribe Email Template Id',
                        accessor: 'unsubscribe_email_template_id',
                        Cell: ({ row }: { row: any }) => {
                            const serviceId = row?.values?.service_id;
                            const templateId = row?.values?.unsubscribe_email_template_id;
                            return <a href={templateLink(serviceId, templateId)}>{templateId}</a>
                        },
                    },

                    {
                        Header: 'Subscribe Phone Template Id',
                        accessor: 'subscribe_phone_template_id',
                        Cell: ({ row }: { row: any }) => {
                            const serviceId = row?.values?.service_id;
                            const templateId = row?.values?.subscribe_phone_template_id;
                            return <a href={templateLink(serviceId, templateId)}>{templateId}</a>
                        },
                    },

                    {
                        Header: 'Unsubscribe Phone Template Id',
                        accessor: 'unsubscribe_phone_template_id',
                        Cell: ({ row }: { row: any }) => {
                            const serviceId = row?.values?.service_id;
                            const templateId = row?.values?.unsubscribe_phone_template_id;
                            return <a href={templateLink(serviceId, templateId)}>{templateId}</a>
                        },
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
                ],
            },
        ],
        []);

    if (loading) {
        return <Spinner />
    }

    return (
        <><Messages /><TableStyles><Table columns={columns} data={lists} /></TableStyles></>
    )
}