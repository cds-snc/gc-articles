/**
 * External dependencies
 */
import * as React from 'react';

/**
 * Internal dependencies
 */
import { ListViewTable } from "./ListViewTable";
import { Messages } from "./Messages";
import { Error } from "./Error";
import { useList } from "../../store";

export const Service = () => {
    const { state: { serviceData } } = useList();

    if (!serviceData) {
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
