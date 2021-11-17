import * as React from "react";
import { Button } from "@wordpress/components";
import { ChevronUp, ChevronDown, Close } from "./Icons";
import { useRepeater } from "../store/RepeaterContext";


export const Panel = ({ item }) => {
    const { state, dispatch } = useRepeater();

    const handleMoveUp = (index) => {
        dispatch({ type: "move_up", payload: { index } });
    };

    const handleMoveDown = (index) => {
        dispatch({ type: "move_down", payload: { index } });
    };

    const handleRemoveItem = (itemId) => {
        dispatch({ type: "remove", payload: { itemId } });
    };

    return (
        <div className="panel-actions">
            <div className="actions">
                {state && state.length >= 2 &&
                    <div className="block-editor-block-mover">
                        <div className="mover components-toolbar-group block-editor-block-mover__move-button-container">
                            <Button style={{ backgroundColor: "#f8f8f8" }} isSmall icon={ChevronUp} onClick={() => handleMoveUp(item.index)} />
                            <Button style={{ backgroundColor: "#f8f8f8" }} isSmall icon={ChevronDown} onClick={() => handleMoveDown(item.index)} />
                        </div>
                    </div>
                }
                <div className="remove" data-id={item.itemId}>
                    <Button
                        isSmall
                        icon={Close}
                        onClick={() => {
                            handleRemoveItem(item.itemId);
                        }}
                    />
                </div>
            </div>
        </div>
    );
};