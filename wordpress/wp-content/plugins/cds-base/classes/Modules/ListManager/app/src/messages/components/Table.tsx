import { __ } from "@wordpress/i18n";
import styled from 'styled-components';
import { useTable, usePagination } from 'react-table';

const StyledPaging = styled.div`
   display:flex;
   padding-top:20px;
   justify-content: flex-end;
`;

export const Table = ({ columns, data }: { columns: any, data: any }) => {
    const {
        getTableProps,
        getTableBodyProps,
        // @ts-ignore
        page,
        prepareRow,
        headerGroups,
        // @ts-ignore
        state: { pageIndex, pageSize  },
        state,
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