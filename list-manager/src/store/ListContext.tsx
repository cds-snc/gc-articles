// @ts-nocheck
import React, { createContext, useReducer, useContext, FC } from 'react'

const ListContext = createContext({
    state: [],
    dispatch: ({ }) => { },
});

const ListReducer = (state = [], action: any) => {
    switch (action.type) {
        case "delete":
            return state.filter((item: Inputs) => {
                return item.id !== action.payload.id
            })
        case "load":
            return [...action.payload];
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