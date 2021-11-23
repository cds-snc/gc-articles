import * as React from "react";
import { Panel } from "./Panel";

export const Item = ({ item, index, children }) => {
    item.index = index;
    return (
        <div className={`item item-${index}`}>
            {children}
            <Panel item={item} />
        </div>
    );
};