// @ts-nocheck
import * as React from 'react';
import { useState, useEffect } from 'react';

import { __ } from "@wordpress/i18n";
import useFetch from 'use-http';
import styled from 'styled-components';
import { Link } from "react-router-dom";

import { Table, StyledPaging, StyledLink } from "./Table";
import { Next } from "./icons/Next";

import { v4 as uuidv4 } from 'uuid';
import { StyledPlaceholder } from './ListTemplates';

const StyledTableLink = styled(Link)`
    text-decoration:underline !important;
    :hover{
        text-decoration:none !important;
    }
`

export const SendingHistory = ({ perPage, pageNav, allLink }: { perPage?: number, pageNav?: boolean, allLink?: boolean }) => {
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);
    const { request, response } = useFetch({ data: [] })

    useEffect(() => {
        const getSentMessages = async () => {
            setLoading(true);
            await request.get(`/messages/sent?${uuidv4()}&sort=desc`);
            if (response.ok) {
                setData(response.data);
                setLoading(false)
            }
        }
        getSentMessages();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);


    const columns = React.useMemo(
        () => [

            {
                Header: __('Message name', "gc-lists"),
                accessor: 'name',
                Cell: ({ row }: { row: any }) => {
                    const messageId = row?.original?.id;
                    const name = row?.original?.name;
                    return (
                        <>
                            <StyledTableLink
                                to={`/messages/edit/${messageId}`}
                            >
                                {name}
                            </StyledTableLink>
                        </>
                    )
                },
            },
            {
                Header: __('Date sent', "gc-lists"),
                accessor: 'created_at',
            },
            {
                Header: __('List name', "gc-lists"),
                accessor: 'sent_to_list_name',
            },
            {
                Header: __('Sender', "gc-lists"),
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
            <h2>{__("Sending history", "gc-lists")}</h2>
            <Table columns={columns} data={data} perPage={perPage} pageNav={pageNav} />
            {allLink && data.length > 6 && <StyledPaging>
                <StyledLink to={`/messages/history`} >
                    <span> {__("All sending history", "gc-lists")} </span><Next />
                </StyledLink>
            </StyledPaging>}
        </> :
        <>
            <h2>{__("Sending history", "gc-lists")}</h2>
            <StyledPlaceholder>
                <h3>{__("You have not sent any message yet.", "gc-lists")}</h3>
                <p>{__("When you send a message, a copy is automatically saved in your sending history.", "gc-lists")}</p>
            </StyledPlaceholder>
        </>
}
