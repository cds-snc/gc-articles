import * as React from "react";
import { createContext, useReducer, useContext } from '@wordpress/element';
import { v4 as uuidv4 } from "uuid";
import { swap } from "../util";

const getPreviousIndex = (items, index) => {
  return index === 0 ? items.length - 1 : index - 1;
};

const getNextIndex = (items, index) => {
  return index === items.length - 1 ? 0 : index + 1;
};

const RepeaterContext = createContext({
  state: [],
  dispatch: ({ }) => { },
  getPreviousIndex,
  getNextIndex
});

const RepeaterReducer = (state, action) => {
  switch (action.type) {
    case "change": {
      return state.map((item, i) => {
        if (action.payload.index !== i) return item;
        const field = action.payload.field;
        return { ...item, [field]: action.payload.value };
      });
    }
    case "move_up": {
      const index = action.payload.index;
      const previous = getPreviousIndex(state, index);
      return [...swap(state, index, previous)];
    }
    case "move_down": {
      const index = action.payload.index;
      const next = getNextIndex(state, index);
      return [...swap(state, index, next)];
    }
    case "remove": {
      return [
        ...state.filter((item) => {
          return item.itemId !== action.payload.itemId;
        })
      ];
    }
    case "add": {
      return [...state, { ...action.payload.value, itemId: uuidv4() }];
    }
    default: {
      throw new Error(`Unhandled action type: ${action.type}`);
    }
  }
};

const RepeaterProvider = ({ children, defaultState }) => {

  defaultState = defaultState.map((item) => {
    return { itemId: uuidv4(), ...item }
  });

  const [state, dispatch] = useReducer(RepeaterReducer, defaultState);

  const value = {
    state,
    dispatch,
    getPreviousIndex,
    getNextIndex
  };

  return (
    <RepeaterContext.Provider value={value}>
      {children}
    </RepeaterContext.Provider>
  );
};

const useRepeater = () => {
  const context = useContext(RepeaterContext);
  if (context === undefined) {
    throw new Error("useRepeater must be used within a RepeaterProvider");
  }
  return context;
};

export { RepeaterProvider, useRepeater };