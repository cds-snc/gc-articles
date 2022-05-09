import * as React from 'react';
import { ListViewTable } from "./ListViewTable";
import { Messages } from "./Messages";
import { useList } from "../store/ListContext";
import { useService } from '../util/useService';
import { Error } from "./Error";

export const Service = () => {
    const { state: { serviceData } } = useList();
    const { serviceId } = useService();

    if (!serviceData) {
        return <Error />;
    }

    if (serviceData[0]?.service_id !== serviceId) {
        return <Error />;
    }

    return (
        <div>
            <Messages />
            <ListViewTable />
        </div>
    )
}

export default Service;