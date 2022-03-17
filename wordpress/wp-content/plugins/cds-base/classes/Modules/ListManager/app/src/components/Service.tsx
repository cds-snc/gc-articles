import * as React from 'react';
import { ListViewTable } from "./ListViewTable";
import { Messages } from "./Messages";

export const Service = () => {
    return (
        <div>
            <Messages />
            <ListViewTable />
        </div>
    )
}

export default Service;