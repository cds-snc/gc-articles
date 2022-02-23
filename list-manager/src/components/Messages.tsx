import React from 'react'
import { useList } from "../store/ListContext";

type Message = {
    id: string,
    message: string;
};

export const Messages = () => {
    const { state, dispatch } = useList();

    let messages: any = []

    if (state.messages && state.messages.length >= 1) {
        messages = state.messages.map((item: Message) => {
            return <div key={item?.id}>{item?.message}</div>
        })
    }

    return messages;
}