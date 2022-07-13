/**
 * External dependencies
 */
import { useEffect, useMemo } from 'react';
import { __ } from "@wordpress/i18n";
import styled from 'styled-components';
import { Link, useLocation } from "react-router-dom";

/**
 * Internal dependencies
 */
import { Table, StyledLink, StyledPaging, ConfirmDelete, Spinner, Next } from ".";
import { useTemplateApi } from '../../store';
import { ToastMessage } from './ToastMessage';

const StyledDivider = styled.span`
    margin-left: 10px;
    margin-right: 10px;
`

const StyledTableLink = styled(Link)`
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

export const StyledPlaceholder = styled.div`
    display:block;
    border: 1px solid #CCCCCC;
    min-width:250px;
    padding:10px;

    h3 {
        display:inline-block;
        font-size: 1.2em;
        margin: 0;
    }

    p {
        margin: 0;
    }
`

export const ListDrafts = ({ perPage, pageNav }: { perPage?: number, pageNav?: boolean }) => {
    const { loading, templates, getTemplates, deleteTemplate } = useTemplateApi();

    useEffect(() => {
        getTemplates();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    const { state: flashMessage = {} } = useLocation();

    const columns = useMemo(
        () => [
            {
                Header: __('Message Name', "gc-lists"),
                accessor: 'name',
                Cell: ({ row }: { row: any }) => {
                    const name = row?.original?.name;
                    return (
                        <strong>{name}</strong>
                    )
                },

            },
            {
                Header: __('Message type', "gc-lists"),
                accessor: 'type',
            },
            {
                Header: __('Last modified', "gc-lists"),
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
                    const message_type = row?.original?.message_type;
                    return (
                        <>
                            <StyledTableLink
                                to={`/messages/edit/${message_type}/${tId}`}
                            >
                                {__("Edit", "gc-lists")}
                            </StyledTableLink>
                            <StyledDivider>|</StyledDivider>
                            <StyledDeleteButton data-tid={tId}
                                onClick={async () => {
                                    const confirmed = await ConfirmDelete();
                                    if (confirmed) {
                                        await deleteTemplate({ templateId: tId });
                                        getTemplates();
                                    }
                                }}
                            >
                                {__("Delete", "gc-lists")}
                            </StyledDeleteButton>
                            <StyledDivider>|</StyledDivider>
                            <StyledTableLink
                                to={`/messages/send/${tId}`}
                            >
                                {__("Send to a list", "gc-lists")}
                            </StyledTableLink>
                        </>
                    )
                },
            },
        ],
        [getTemplates, deleteTemplate]
    );

    return (
        <>
            {window.location.hash === '#/messages' &&
                <h1>{__('Messages', 'gc-lists')}</h1>
            }

            {/* @ts-ignore */}
            {flashMessage && <ToastMessage state={{ messages: [{ id: flashMessage?.id, type: flashMessage.type, message: `Saved` }] }} />}

            <Link
                className="button button-primary"
                to={`/messages/choose`}
            >
                {__("Create new message", "gc-lists")}
            </Link>
            <h2>{__('Draft messages', 'gc-lists')}</h2>
            {loading && <Spinner />}

            {
                !loading && templates?.length ?
                    <>
                        <Table columns={columns} data={templates} perPage={perPage} pageNav={pageNav} />
                        {templates?.length > 6 &&
                            <StyledPaging>
                                <StyledLink to={`/messages/all-drafts`}>
                                    <span>{__("All draft messages", "gc-lists")}</span><Next />
                                </StyledLink>
                            </StyledPaging>
                        }
                    </>
                    :
                    <>
                        {!loading &&
                            <>
                                <StyledPlaceholder>
                                    <h3>{__("You have no draft messages", "gc-lists")}</h3>
                                    <p>{__("Saving a draft allows you to keep a message you aren't ready to send yet.", "gc-lists")}</p>
                                </StyledPlaceholder>
                            </>
                        }
                    </>
            }
        </>
    )
}

export default ListDrafts;
