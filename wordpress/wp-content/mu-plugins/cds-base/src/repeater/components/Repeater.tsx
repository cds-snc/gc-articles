import * as React from "react";
import { useRepeater } from "../store/RepeaterContext";
import { Item } from "./Item";
import { ListItem, ServiceItem } from "../../repeater/RepeaterForm";

export const Repeater = ({ render }) => {
    const { state } = useRepeater();
    return (
        <div className="items">
            {state.map((item: ListItem | ServiceItem, index) => {
                return (
                    <div key={item.itemId}>
                        <Item item={item} index={index}>
                            {render(item)}
                        </Item>
                    </div>)
            })}
        </div>
    );
};