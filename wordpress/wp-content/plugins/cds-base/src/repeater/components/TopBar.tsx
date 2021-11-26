import * as React from "react";
import { Button } from "@wordpress/components";
import { useRepeater } from "../store/RepeaterContext";

export const TopBar = ({ addLabel, emptyItem }) => {
  const { dispatch } = useRepeater();
  const handleAddItem = () => {
    dispatch({ type: "add", payload: { value: emptyItem } });
  };

  return (
    <div className="top-bar">
      <div className="content">
        <Button isPrimary onClick={handleAddItem}>
          {addLabel}
        </Button>
      </div>
      <div className="one-edge-shadow fadedScroller_fade"></div>
    </div>
  );
};