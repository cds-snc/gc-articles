/**
 * External dependencies
 */
import * as React from 'react';
import { useParams } from "react-router-dom";

/**
 * Internal dependencies
 */
import { useList } from './listContext';

export const useService = () => {
    const { state: { serviceData } } = useList();
    const params = useParams();
    // @ts-ignore
    const serviceId = serviceData?.service_id;
    const listId = params?.listId;
    const type = params?.type;
    return { serviceId, listId, type };
}
