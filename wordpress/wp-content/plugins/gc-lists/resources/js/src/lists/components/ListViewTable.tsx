/**
 * External dependencies
 */
import { useMemo } from "react";
import styled from 'styled-components';
import { useTable } from 'react-table';
import { Link } from "react-router-dom";
import { __ } from "@wordpress/i18n";

/**
 * Internal dependencies
 */
import { DeleteLink } from './DeleteLink';
import { List, ListType } from "../../types"
import { useList, useListFetch } from '../../store';
import { getListType } from "../../util/functions";

const StyledCreateButton = styled(Link)`
    margin-bottom: 20px !important;
`

const StyledActionLink = styled(Link)`
    text-decoration:underline !important;
    :hover{
        text-decoration:none !important;
    }
`
const StyledDivider = styled.span`
    margin-left: 10px;
    margin-right: 10px;
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

const updateLink = (listId: string) => {
    return `/lists/${listId}/update`;
}

const EditLink = ({ listId }: { listId: string }) => {
    return (
        <StyledActionLink to={{ pathname: updateLink(listId) }}>
            {__('Edit', 'gc-lists')}
        </StyledActionLink>
    )
}

const UploadListLink = ({ listId, type }: { listId: string, type: ListType }) => {
    return (
        <StyledActionLink to={{ pathname: `/lists/${listId}/upload/${type}` }}>
            {__('Add subscribers', 'gc-lists')}
        </StyledActionLink>
    )
}

const StyledNoLists = styled.div`
    padding:10px;
    border: 1px solid rgb(204, 204, 204);

    p{
        margin-top:0px;
        margin-bottom:5px;
    }
`;

const NoLists = () => {
    return (
        <StyledNoLists>
            <p><strong>{__("You have no mailing lists.", "gc-lists")}</strong></p>
            <p>{__("A mailing list allows you to collect a group of subscribers that you can send messages to.", "gc-lists")}</p>
        </StyledNoLists>
    )
}

export const ListViewTable = () => {
    const { state: { lists, user, hasLists } } = useList();
    const { status } = useListFetch();
    const columns = useMemo(
        () =>
            [{
                Header: __('List Name', "gc-lists"),
                accessor: 'name',
                Cell: ({ row }: { row: any }) => {
                    return (
                        <strong>
                            <Link
                                className="row-title"
                                to={{
                                    pathname: updateLink(row?.original?.id),
                                }}
                            >
                                {row?.values?.name}
                            </Link>
                        </strong>
                    )
                },
            },
            {
                Header: __('Subscribers', "gc-lists"),
                accessor: 'subscriber_count',
            },
            {
                accessor: 'active',
                Cell: ({ row }: { row: any }) => {
                    return <>
                        <EditLink listId={`${row?.original?.id}`} />
                        <StyledDivider>|</StyledDivider>
                        {user?.isSuperAdmin && <><DeleteLink listId={`${row?.original?.id}`} /> <StyledDivider>|</StyledDivider> </>}
                        {getListType(row?.original?.language) === ListType.EMAIL && <UploadListLink listId={`${row?.original?.id}`} type={ListType.EMAIL} />}
                        {getListType(row?.original?.language) === ListType.PHONE && user?.hasPhone && <UploadListLink listId={`${row?.original?.id}`} type={ListType.PHONE} />}
                    </>
                },
            },
            ], [user?.hasPhone, user?.isSuperAdmin]);


    if (status === "error") {
        return (
            <div className="error-summary components-notice is-error">
                <div className="components-notice__content">
                    <h2>{__('Error something went wrong', 'gc-lists')}</h2>
                </div>
            </div>
        )
    }

    return (
        <>
            <StyledCreateButton
                className="button button-primary"
                to={{ pathname: `/lists/create` }}>
                {__('Create new list', 'gc-lists')}
            </StyledCreateButton>

            {hasLists ? <Table columns={columns} data={lists} /> : <NoLists />}
        </>
    )
}

export default ListViewTable;
