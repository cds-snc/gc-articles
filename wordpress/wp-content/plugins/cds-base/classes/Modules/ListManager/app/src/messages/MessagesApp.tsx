import * as React from 'react';
import { Routes, Route } from "react-router-dom";
import { Spinner } from '../common/Spinner';
const Home = React.lazy(() => import("./main/Home"));
const EditTemplate = React.lazy(() => import("./components/EditTemplate"));
const SendTemplate = React.lazy(() => import("./components/SendTemplate"));

// route http://localhost:3000/#/messages/123/edit/123
const MessagesApp = () => {
    return (
        <Routes>
            <Route path=":serviceId" element={
                <React.Suspense fallback={<Spinner />}>
                    <Home />
                </React.Suspense>
            } />
            <Route path=":serviceId/edit/:templateId" element={
                <React.Suspense fallback={<Spinner />}>
                    <EditTemplate />
                </React.Suspense>
            } />
            <Route path=":serviceId/send/:templateId" element={
                <React.Suspense fallback={<Spinner />}>
                    <SendTemplate />
                </React.Suspense>
            } />
        </Routes>
    )
}

export default MessagesApp;