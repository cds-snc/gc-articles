import * as React from 'react';

import styled from 'styled-components';
import { useTable } from 'react-table';
import { Link } from "react-router-dom";

import { List, ListType } from "../types"
import { useList } from "../store/ListContext";
import { Spinner } from './Spinner';
import { DeleteActionLink } from './DeleteActionLink';
import { ResetActionLink } from './ResetActionLink';
import { useListFetch } from '../store/UseListFetch';
import { useService } from '../util/useService';
import { capitalize, getListType } from "../util";

const HeaderStyles = styled.div`
    display: flex;
    justify-content: space-between;
`

const UploadButton = styled.div`
    margin: 0px .5rem .5rem;
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

const UploadListLink = ({ name, listId, serviceId, type }: { name: string, listId: string, serviceId: string | undefined, type: ListType }) => {
    return <UploadButton><Link aria-label={`${name} upload list`} className="button action" to={{ pathname: `/service/${serviceId}/list/${listId}/upload/${type}` }}>{capitalize(type)}</Link></UploadButton>
}

const updateLink = (serviceId: string | undefined, listId: string) => {
    return `/service/${serviceId}/list/${listId}/update`;
}

export const ListViewTable = () => {
    const { state: { lists, user } } = useList();
    const { status } = useListFetch();
    const { serviceId } = useService();
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
                                            pathname: updateLink(serviceId, row?.original?.id),
                                        }}
                                    >
                                        {row?.values?.name}
                                    </Link>
                                </strong>
                            )
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
                            return (<DeleteActionLink id={`${row?.original?.id}`} />)
                        },
                    },
                    {
                        Header: 'Reset',
                        accessor: 'reset',
                        Cell: ({ row }: { row: any }) => {
                            return (<ResetActionLink id={`${row?.original?.id}`} />);
                        },
                    },

                    {
                        Header: 'Upload',
                        accessor: 'active',
                        Cell: ({ row }: { row: any }) => {
                            return <>
                                {getListType(row?.original?.language) === ListType.EMAIL && <UploadListLink name={`${row?.values?.name}`} listId={`${row?.original?.id}`} serviceId={serviceId} type={ListType.EMAIL} />}
                                {getListType(row?.original?.language) === ListType.PHONE && user?.hasPhone ? <UploadListLink name={`${row?.values?.name}`} listId={`${row?.original?.id}`} serviceId={serviceId} type={ListType.PHONE} /> : null}
                            </>
                        },
                    },
                ],
            },
        ],
        [serviceId, user?.hasPhone]);


    if (status === "error") {
        return (
            <div className="error-summary components-notice is-error">
                <div className="components-notice__content">
                    <h2>Error something went wrong</h2>
                </div>
            </div>
        )
    }

    if (status === "loading") {
        return <Spinner />
    }

    return (
        <Table columns={columns} data={lists} />
    )
}

export default ListViewTable;