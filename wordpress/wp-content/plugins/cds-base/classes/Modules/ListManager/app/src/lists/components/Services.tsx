import * as React from 'react';
import { useList } from "../store/ListContext";
import { Navigate } from "react-router-dom";

export const Services = () => {
    const { state: { serviceData } } = useList();

    if (!serviceData || serviceData?.length < 1) {
        return <><p>No services found.</p></>
    }
    return <Navigate to={{ pathname: `/service/${serviceData[0].service_id}` }} />
}