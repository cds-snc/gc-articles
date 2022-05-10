import * as React from 'react';
import { Routes, Route } from "react-router-dom";
import { Main } from "./components/Main";
import { Spinner } from '../common/Spinner';
const SendTemplate = React.lazy(() => import("./components/SendTemplate"));
const MessagesApp = () => {
    return (
        <Routes>
            <Route path="/" element={<Main />} />
            <Route path=":serviceId/send" element={
                <React.Suspense fallback={<Spinner />}>
                    <SendTemplate />
                </React.Suspense>
            } />
        </Routes>
    )
}

export default MessagesApp;