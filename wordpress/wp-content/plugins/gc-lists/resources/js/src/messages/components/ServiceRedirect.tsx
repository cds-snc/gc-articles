import * as React from 'react';
import { Navigate } from "react-router-dom";
import { useList } from "../../store/ListContext";

export const ServiceRedirect = () => {

    const { state: { serviceData } } = useList();

    if (!serviceData || serviceData?.length < 1) {
        return <><p>No services found.</p></>
    }
    return <Navigate to={{ pathname: `/messages/${serviceData[0].service_id}` }} />
}

export default ServiceRedirect;