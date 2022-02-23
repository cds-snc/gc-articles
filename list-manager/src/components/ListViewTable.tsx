import React, { useState, useEffect, useCallback } from 'react'
import styled from 'styled-components'
import { useTable } from 'react-table';
import { Link } from "react-router-dom";
import useFetch from 'use-http';
import { ConfirmActionLink } from './ConfirmActionLink';
import { Spinner } from './Spinner'

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

const Table = ({ columns, data }: { columns: any, data: any }) => {
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

export const ListViewTable = () => {
    const { request, response, error, loading } = useFetch({ data: [] })
    const [data, setData] = useState([])

    const loadData = useCallback(async () => {
        await request.get('/lists')

        if (response.ok) {
            setData(await response.json())
        }

    }, [response, request]);

    useEffect(() => { loadData() }, [loadData]) // componentDidMount

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
                        // GET /lists/{service_id}
                        // GET /lists/{service_id}/subscriber-count/
                    },

                    {
                        Header: 'Subscribe Email Template Id',
                        accessor: 'subscribe_email_template_id',
                    },

                    {
                        Header: 'Unsubscribe Email Template Id',
                        accessor: 'unsubscribe_email_template_id',
                    },

                    {
                        Header: 'Subscribe Phone Template Id',
                        accessor: 'subscribe_phone_template_id',
                    },

                    {
                        Header: 'Unsubscribe Phone Template Id',
                        accessor: 'unsubscribe_phone_template_id',
                    },
                    {
                        Header: 'Delete',
                        accessor: 'delete',
                        Cell: ({ row }: { row: any }) => {
                            /*
                            // @todo "inline delete" to prevent re-draw
                            return <a href="#" onClick={(e) => {
                                // if confirm
                                // inline delete --- + make request.delete call without navigating
                                e.preventDefault();
                                const newData = data.filter((item: Inputs) => {
                                    return item.id !== row?.values?.id
                                })
                                setData(newData)
                            }}> Delete</a>
                            */

                            return (<ConfirmActionLink text="delete" path={`list/${row?.values?.id}/delete`} />)
                        },
                    },
                    {
                        Header: 'Reset',
                        accessor: 'reset',
                        Cell: ({ row }: { row: any }) => {
                            return (<ConfirmActionLink text="reset" path={`list/${row?.values?.id}/reset`} />)
                        },
                    },
                ],
            },

        ],
        [data]);


    return (
        <>
            {error && 'Error!'}
            {loading && <Spinner />}
            <TableStyles><Table columns={columns} data={data} /></TableStyles>
        </>
    )
}