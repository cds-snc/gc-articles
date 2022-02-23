// @ts-nocheck
import React, { createContext, useReducer, useContext, FC } from 'react'
import { v4 as uuidv4 } from "uuid";
import {Inputs} from "../types"

const ListContext = createContext({
    state: { lists: [], messages: [] },
    dispatch: ({ }) => { return null },
});

const ListReducer = (state, action: any) => {

    switch (action.type) {
        case "add":
            console.log("add");
            return { ...state, messages: [{ id: uuidv4(), type: "add", message: `Added ${action.payload.id}` }] }
        case "delete":
            console.log("delete");
            const lists = state.lists.filter((item: Inputs) => {
                return item.id !== action.payload.id
            })
            return { ...state, lists, messages: [{ id: uuidv4(), type: "delete", message: `Deleted  ${action.payload.id}` }] }
        case "load":
            return { ...state, lists: [...action.payload] };
        default: {
            throw new Error(`Unhandled action type: ${action.type}`);
        }
    }
};

const ListProvider: FC = ({ children },) => {

    const [state, dispatch] = useReducer(ListReducer, []);

    const value = {
        state,
        dispatch,
    };

    return (
        <ListContext.Provider value={value}>
            {children}
        </ListContext.Provider>
    );
};

const useList = () => {
    const context = useContext(ListContext);
    if (context === undefined) {
        throw new Error("useList must be used within a ListProvider");
    }
    return context;
};

export { ListProvider, useList };