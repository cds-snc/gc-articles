import localForage from "localforage";
import { useEffect } from 'react';

const localDb = () => {
    const store = localForage.createInstance({
        name: "gclists"
    });

    return store;
}

export const Main = () => {
    useEffect(() => {
        const db = localDb();

        const getItems = async () => {
            console.log(await db.getItem('123'));
        }
        getItems();
        db.setItem('123', { title: "title 123", subject: "subject 123", content: "content 123", timestamp: new Date().getTime() })
    }, []);


    return <div>Messages app here</div>
}