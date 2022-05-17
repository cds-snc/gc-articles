import * as React from 'react';
import styled from 'styled-components';
import { useState, useEffect, useCallback } from 'react';
import { __ } from "@wordpress/i18n";
import { v4 as uuidv4 } from 'uuid';
import { Link } from "react-router-dom";
import { useTable, usePagination } from 'react-table';
import { format } from "date-fns"

import { useService } from '../../util/useService';
import useTemplateApi from '../../store/useTemplateApi';

const StyledH1 = styled.h1`
   margin-bottom:30px !important;
`

const StyledDivider = styled.span`
    margin-left: 10px;
    margin-right: 10px;
`

const StyledLink = styled(Link)`
    text-decoration:underline !important;
    :hover{
        text-decoration:none !important;
    }
`
const StyledDeleteButton = styled.button`
    color: #D3080C;
    background: none;
    border: none;
    cursor: pointer;
    text-decoration:underline;
    :hover{
        text-decoration:none;
    }
`;

const StyledPaging = styled.div`
   display:flex;
   padding-top:20px;
   justify-content: flex-end;
`;

const Table = ({ columns, data }: { columns: any, data: any }) => {
    const {
        getTableProps,
        getTableBodyProps,
        // @ts-ignore
        page,
        prepareRow,
        headerGroups,
        // @ts-ignore
        state: { pageIndex, pageSize },
    } = useTable({
        columns,
        data,
        // @ts-ignore
        initialState: { pageSize: 6 },
    }, usePagination)

    return (
        <>
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
                    { /* @ts-ignore */}
                    {page.map((row, i) => {
                        prepareRow(row)
                        return (
                            <tr {...row.getRowProps()}>
                                { /* @ts-ignore */}
                                {row.cells.map(cell => {
                                    return <td {...cell.getCellProps()}>{cell.render('Cell')}</td>
                                })}
                            </tr>
                        )
                    })}
                </tbody>
            </table>
            <StyledPaging>
                <div>{__("Showing", "cds-snc")} {pageIndex + 1}-{pageSize} of {data.length}</div>
            </StyledPaging>
        </>
    )
}

export const ListTemplates = () => {
    const [templates, setTemplates] = useState([]);
    const { getTemplates, deleteTemplate } = useTemplateApi();
    const { serviceId } = useService();

    const fetchTempates = useCallback(async () => {
        setTemplates(await getTemplates());
    }, [getTemplates]);

    useEffect(() => {
        fetchTempates();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    const columns = React.useMemo(
        () => [
            {
                Header: __('Message Name'),
                accessor: 'name',
                Cell: ({ row }: { row: any }) => {
                    const name = row?.original?.name;
                    return (
                        <strong>{name}</strong>
                    )
                },

            },
            {
                Header: __('Message type'),
                accessor: 'type',
            },
            {
                Header: __('Last modified'),
                accessor: 'timestamp',
                Cell: ({ row }: { row: any }) => {
                    const t = row?.original?.timestamp;
                    const date = format(new Date(t), "yyyy/mm/dd");
                    const time = format(new Date(t), "hh:mm a");
                    return (
                        <>
                            {`${date} at ${time}`}
                        </>
                    )
                },
            },
            {
                Header: '',
                accessor: 'templateId',
                Cell: ({ row }: { row: any }) => {
                    const tId = row?.original?.templateId;
                    return (
                        <>
                            <StyledLink to={{ pathname: `/messages/${serviceId}/edit/${tId}` }}>
                                {__("Edit")}
                            </StyledLink>
                            <StyledDivider>|</StyledDivider>
                            <StyledDeleteButton
                                onClick={async () => {
                                    await deleteTemplate({ templateId: tId });
                                    fetchTempates();
                                }}
                            >
                                {__("Delete")}
                            </StyledDeleteButton>
                            <StyledDivider>|</StyledDivider>
                            <StyledLink to={{ pathname: `/messages/${serviceId}/send/${tId}` }}>
                                {__("Send Template")}
                            </StyledLink>
                        </>
                    )
                },
            },
        ],
        [deleteTemplate, fetchTempates, serviceId]
    )

    return (
        <>
            <StyledH1>{__('Messages', 'cds-snc')}</StyledH1>
            <Link className="button button-primary" to={{ pathname: `/messages/${serviceId}/edit/${uuidv4()}` }}>{__("Create Template")}</Link>
            {
                templates?.length ?
                    <>
                        <h2>{__('Message templates', 'cds-snc')}</h2>
                        <Table columns={columns} data={templates} />
                    </> : null
            }
            <>
                <strong>

                </strong>
            </>
        </>
    )
}

export default ListTemplates;