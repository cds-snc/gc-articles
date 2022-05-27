// @ts-nocheck
import * as React from 'react';
import { useState, useEffect } from 'react';

import { __ } from "@wordpress/i18n";
import useFetch from 'use-http';
import styled from 'styled-components';
import { Link } from "react-router-dom";
import { v4 as uuidv4 } from "uuid";

import { Table, StyledPaging, StyledLink } from "./Table";
import { Next } from "./icons/Next";
import { useService } from '../../util/useService';

const StyledTableLink = styled(Link)`
    text-decoration:underline !important;
    :hover{
        text-decoration:none !important;
    }
`

export const SendingHistory = ({ perPage, pageNav }: { perPage?: number, pageNav?: boolean }) => {
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);
    const { serviceId } = useService();
    const { request, response } = useFetch({ data: [] })

    useEffect(() => {
        const getSentMessages = async () => {
            setLoading(true);
            await request.get(`/messages/sent?c=${uuidv4()}`);
            if (response.ok) {
                console.log(await response.json());
                setData(await response.json());
                setLoading(false)
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
                Cell: ({ row }: { row: any }) => {
                    const messageId = row?.original?.id;
                    const name = row?.original?.name;
                    return (
                        <>
                            <StyledTableLink
                                to={`/messages/${serviceId}/${messageId}/versions`}
                            >
                                {name}
                            </StyledTableLink>
                        </>
                    )
                },
            },
            {
                Header: __('Date sent', "cds-snc"),
                accessor: 'created_at',
            },
            {
                Header: __('List name', "cds-snc"),
                accessor: 'sent_to_list_name',
            },
            {
                Header: __('Sender', "cds-snc"),
                accessor: 'sent_by_email',
            },
        ],
        []
    )

    if (loading) {
        return null;
    }

    return data.length ?
        <>
            <h2> {__("Sending History", "cds-snc")}</h2 >
            <Table columns={columns} data={data} perPage={perPage} pageNav={pageNav} />
            <StyledPaging>
                <StyledLink to={`/messages/${serviceId}/history`} >
                    <span> {__("All sending history", "cds-snc")} </span><Next />
                </StyledLink>
            </StyledPaging>
        </> : null
}