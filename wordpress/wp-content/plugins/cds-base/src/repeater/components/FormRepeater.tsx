import * as React from "react";
import { TopBar } from "./TopBar";
import { RepeaterProvider } from "../store/RepeaterContext";
import { Repeater } from "./Repeater";

export const FormRepeater = ({ formType, addLabel, defaultState, emptyItem }) => {

  const Form = formType;

  if (!defaultState?.length) {
    return null
  }

  return (
    <RepeaterProvider defaultState={defaultState}>
      <div className="settings-repeater">
        <hr />
        <TopBar addLabel={addLabel} emptyItem={emptyItem} />
        <Repeater render={(item) => <Form item={item} />} />
        <hr />
      </div>
    </RepeaterProvider>
  );
}
