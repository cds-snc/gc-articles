import * as React from 'react';
import { ListViewTable } from "./ListViewTable";
import { useList } from "../store/ListContext";
import { Messages } from "./Messages";
import { ServiceData, Service as ServiceType } from "../types";
import { useParams } from "react-router-dom";

const getServiceName = (serviceData: ServiceData, serviceId: string | undefined): string => {
    if (!serviceId) return "";

    const currentService: ServiceType[] | undefined = serviceData?.filter((service: ServiceType) => {
        return service['service_id'] === serviceId
    });

    if (currentService?.length) {
        return currentService[0].name;
    }

    return "";
}

export const Service = () => {
    const { state: { serviceData } } = useList();
    const params = useParams();
    return (
        <div>
            <Messages />
            <ListViewTable />
        </div>
    )
}

export default Service;