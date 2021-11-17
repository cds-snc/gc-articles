import * as React from "react";

import { useRepeater } from "../store/RepeaterContext";
export const NotifyServicesForm = ({ item }) => {
  const { state, dispatch } = useRepeater();

  const handleChange = (field, e, index) => {
    dispatch({
      type: "change",
      payload: { field, value: e.target.value, index }
    });
  };

  let cleanedState = "";

  // map cleanedState to existing format
  state.forEach((item, index) => {
    cleanedState += `${item.name}~${item.apiKey},`;
  });

  cleanedState = cleanedState.slice(0, -1);

  return (
    <div>
      {/* this element contains the value to save to the database */}
      <input id="list_manager_notify_services" name="LIST_MANAGER_NOTIFY_SERVICES" type="hidden" value={cleanedState} />
      <label htmlFor={`name-${item.index}`}>{`Name`}:</label>
      <input
        type="text"
        name={`name-${item.index}`}
        value={item.name}
        onChange={(e) => {
          handleChange("name", e, item.index);
        }}
      />

      <label htmlFor={`apiKey-${item.index}`}>{`API Key`}:</label>
      {item.hint && <div dangerouslySetInnerHTML={{ __html: item.hint }} />}
      <input
        type="text"
        name={`apiKey-${item.index}`}
        className="api-key"
        value={item.apiKey}
        onChange={(e) => {
          handleChange("apiKey", e, item.index);
        }}
      />
    </div>
  );
};