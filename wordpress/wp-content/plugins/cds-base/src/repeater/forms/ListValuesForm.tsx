import * as React from "react";
import { useRepeater } from "../store/RepeaterContext";
export const ListValuesForm = ({ item }) => {
  const { state, dispatch } = useRepeater();

  const handleChange = (field, e, index) => {
    dispatch({
      type: "change",
      payload: { field, value: e.target.value, index }
    });
  };

  const cleanedState = state.map((item) => {
    return (({ label, id, type }) => ({ label, id, type }))(item);
  });

  return (
    <div>
      {/* this element contains the value to save to the database */}
      <input id="list-values" name="list_values" type="hidden" value={JSON.stringify(cleanedState)} />

      <label htmlFor={`label-${item.index}`}>{`Label`}:</label>
      <input
        type="text"
        name={`label-${item.index}`}
        value={item.label}
        onChange={(e) => {
          handleChange("label", e, item.index);
        }}
      />

      <label htmlFor={`id-${item.index}`}>{`ID`}:</label>
      <input
        type="text"
        name={`id-${item.index}`}
        value={item.id}
        onChange={(e) => {
          handleChange("id", e, item.index);
        }}
      />

      <label htmlFor={`type-${item.index}`}>{`Type`}:</label>
      <input
        type="text"
        name={`type-${item.index}`}
        value={item.type}
        onChange={(e) => {
          handleChange("type", e, item.index);
        }}
      />
    </div>
  );
};