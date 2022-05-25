import * as React from 'react';
import { useState, useEffect, useCallback } from 'react';
import { __ } from "@wordpress/i18n";
import styled from 'styled-components';
import { Link } from "react-router-dom";

import { Table } from "./Table";
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

export const ListTemplates = ({ perPage, pageNav }: { perPage?: number, pageNav?: boolean }) => {
    const [templates, setTemplates] = useState([]);
    const { getTemplates, deleteTemplate } = useTemplateApi();
    const { serviceId } = useService();

    const fetchTempates = useCallback(async () => {
        try {
            const result = await getTemplates();
            setTemplates(result);
        } catch (e) {
            console.log(e);
        }
    }, [getTemplates]);

    useEffect(() => {
        fetchTempates();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    const columns = React.useMemo(
        () => [
            {
                Header: __('Message Name', "cds-snc"),
                accessor: 'name',
                Cell: ({ row }: { row: any }) => {
                    const name = row?.original?.name;
                    return (
                        <strong>{name}</strong>
                    )
                },

            },
            {
                Header: __('Message type', "cds-snc"),
                accessor: 'type',
            },
            {
                Header: __('Last modified', "cds-snc"),
                accessor: 'updated_at',
                Cell: ({ row }: { row: any }) => {
                    const t = row?.original?.updated_at;
                    // const date = format(new Date(t), "yyyy/mm/dd");
                    // const time = format(new Date(t), "hh:mm a");
                    return (
                        <>
                            {t}
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
                            <StyledLink
                                to={`/messages/${serviceId}/edit/${tId}`}
                            >
                                {__("Edit", "cds-snc")}
                            </StyledLink>
                            <StyledDivider>|</StyledDivider>
                            <StyledDeleteButton data-tid={tId}
                                onClick={async () => {
                                    await deleteTemplate({ templateId: tId });
                                    await fetchTempates();
                                }}
                            >
                                {__("Delete", "cds-snc")}
                            </StyledDeleteButton>
                            <StyledDivider>|</StyledDivider>
                            <StyledLink
                                to={`/messages/${serviceId}/send/${tId}`}
                            >
                                {__("Send Template", "cds-snc")}
                            </StyledLink>
                        </>
                    )
                },
            },
        ],
        [deleteTemplate, fetchTempates, serviceId]
    );

    return (
        <>
            <StyledH1>{__('Messages', 'cds-snc')}</StyledH1>
            <Link
                className="button button-primary"
                to={`/messages/${serviceId}/edit/new`}
            >
                {__("Create Template", "cds-snc")}
            </Link>
            {
                templates?.length ?
                    <>
                        <h2>{__('Message templates', 'cds-snc')}</h2>
                        <Table columns={columns} data={templates} perPage={perPage} pageNav={pageNav} />
                    </> : null
            }
        </>
    )
}

export default ListTemplates;