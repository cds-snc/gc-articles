/**
 * External dependencies
 */
import * as React from 'react';
import { createContext, useReducer, useContext } from 'react';
import { v4 as uuidv4 } from "uuid";

/**
 * Internal dependencies
 */
import { List, State, Dispatch, Action, ListProviderProps } from "../types";

const ListContext = createContext<{ state: State; dispatch: Dispatch } | undefined>(undefined)

const ListReducer = (state: State, action: Action): State => {
    switch (action.type) {
        case "add": {
            return { ...state, messages: [{ id: uuidv4(), type: "add", message: `Added ${action.payload.id}` }] }
        }
        case "reset": {
            const lists = state.lists.filter((item: List) => {
                if (item.id === action.payload.id) {
                    item.subscriber_count = "0";
                }

                return item;
            })
            return { ...state, lists, messages: [{ id: uuidv4(), type: "reset", message: `Reset list ${action.payload.id}` }] }
        }
        case "delete": {
            const lists = state.lists.filter((item: List) => {
                return item.id !== action.payload.id
            })

            let hasLists = true;
            if (lists?.length < 1) {
                hasLists = false;
            } else {

            }
            return { ...state, hasLists, lists, messages: [{ id: uuidv4(), type: "delete", message: `Deleted  ${action.payload.id}` }] }
        }
        case "load": {
            return { ...state, lists: [...action.payload], hasLists: true };
        }
        case "no-lists": {
            return { ...state, hasLists: false };
        }
        default:
            throw new Error(`Unhandled action type`);
    }
};

const ListProvider = ({ children, serviceData, user = { hasEmail: true, hasPhone: false, isSuperAdmin: false }, config = { listManagerApiPrefix: '' } }: ListProviderProps) => {
    const [state, dispatch] = useReducer(ListReducer, { loading: false, serviceData, lists: [], hasLists: false, messages: [], user: user, config });

    const value = {
        state,
        dispatch,
    }

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
