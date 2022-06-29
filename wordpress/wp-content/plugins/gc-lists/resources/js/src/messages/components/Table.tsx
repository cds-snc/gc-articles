/**
 * External dependencies
 */
import * as React from 'react';
import { __ } from "@wordpress/i18n";
import styled from 'styled-components';
import { useTable, usePagination } from 'react-table';
import { Link } from "react-router-dom";

/**
 * Internal dependencies
 */
import { Next, Back } from ".";

export const StyledH1 = styled.h1`
   margin-bottom:30px !important;
`

export const StyledPaging = styled.div`
   font-size:16px;
   display:flex;
   justify-content: flex-end;
`;

export const StyledPageTotals = styled.div`
   padding-top:20px;
   margin-right:10px;
`;

export const StyledButton = styled.button`
    position:relative;
    color: #284162;
    display:flex;
    align-items: center;
    justify-content: center;
    background: none;
    border: none;
    cursor: pointer;
    text-decoration:underline;
    :hover{
        text-decoration:none;
    }

    :disabled{
        display:none;
    }

    span{
        position:relative;
        padding: 5px;
        top:-1px;
    }
`;

export const StyledLink = styled(Link)`
    position:relative;
    display:block;
    margin-top:20px;
    margin-bottom: 20px;
    span{
        display:inline-block;
        text-decoration:underline !important;
        position:relative;
        top:-1px;
        margin-right:10px;

        :hover{
            text-decoration:none !important;
        }
    }
`

export const Table = ({ columns, data, perPage = 6, pageNav = false }: { columns: any, data: any, perPage?: number, pageNav?: boolean }) => {
    const {
        getTableProps,
        getTableBodyProps,
        // @ts-ignore
        page,
        prepareRow,
        headerGroups,
        // @ts-ignore
        canPreviousPage,
        // @ts-ignore
        canNextPage,
        // @ts-ignore
        state: { pageIndex, pageSize },
        // @ts-ignore
        nextPage,
        // @ts-ignore
        previousPage,
    } = useTable({
        columns,
        data,
        // @ts-ignore
        initialState: { pageSize: perPage },
    }, usePagination);

    const pageCurrent = (pageIndex * pageSize) + 1;
    const pageTotal = Math.min((pageCurrent - 1) + pageSize, data.length);

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
                <StyledPageTotals>
                    {__("Showing", "gc-lists")} {pageCurrent}{" - "}{pageTotal} {__("of", "gc-lists")} {data.length}
                </StyledPageTotals>
            </StyledPaging>

            {pageNav &&
                <StyledPaging>
                    <StyledButton onClick={() => previousPage()} disabled={!canPreviousPage}>
                        <Back /> <span>{__("previous", "gc-lists")}</span>
                    </StyledButton>{' '}

                    <StyledButton onClick={() => nextPage()} disabled={!canNextPage}>
                        <span>{__("next", "gc-lists")}</span><Next />
                    </StyledButton>
                </StyledPaging>}
        </>
    )
}
