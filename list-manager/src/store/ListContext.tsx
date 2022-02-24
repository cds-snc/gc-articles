import { createContext, useReducer, useContext, useEffect, useState } from 'react';
import useFetch from 'use-http';
import { v4 as uuidv4 } from "uuid";
import { List, State, Dispatch, Action, ListProviderProps } from "../types"

const ListContext = createContext<{ state: State; dispatch: Dispatch, loading: boolean } | undefined>(undefined)

const ListReducer = (state: State, action: Action): State => {
    switch (action.type) {
        case "add":
            return { ...state, messages: [{ id: uuidv4(), type: "add", message: `Added ${action.payload.id}` }] }
        case "delete":
            const lists = state.lists.filter((item: List) => {
                return item.id !== action.payload.id
            })
            return { ...state, lists, messages: [{ id: uuidv4(), type: "delete", message: `Deleted  ${action.payload.id}` }] }
        case "load":
            return { ...state, lists: [...action.payload] };
        default:
            throw new Error(`Unhandled action type`);
    }
};

const ListProvider = ({ children }: ListProviderProps) => {
    const [loading, setLoading] = useState(false);
    const { request, response } = useFetch({ data: [] })
    const [state, dispatch] = useReducer(ListReducer, { lists: [], messages: [] });

    useEffect(() => {
        (async (): Promise<void> => {
            setLoading(true)
            await request.get('/lists')
            if (response.ok) {
                dispatch({ type: "load", payload: await response.json() })
                setLoading(false)
            }
        })();
    }, [request, response]);

    const value = {
        loading,
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