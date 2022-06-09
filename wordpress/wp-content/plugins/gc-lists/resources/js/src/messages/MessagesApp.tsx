import * as React from 'react';
import { Routes, Route } from "react-router-dom";
import { Spinner } from '../common/Spinner';
const Home = React.lazy(() => import("./main/Home"));
const EditTemplate = React.lazy(() => import("./components/EditTemplate"));
const SendTemplate = React.lazy(() => import("./components/SendTemplate"));
const AllTemplates = React.lazy(() => import("./components/AllTemplates"));
const AllSendingHistory = React.lazy(() => import("./components/AllSendingHistory"));
const Versions = React.lazy(() => import("./components/Versions"));

// route http://localhost:3000/#/messages/123/edit/123
const MessagesApp = () => {
    return (
        <Routes>
            <Route path="" element={
                <React.Suspense fallback={<Spinner />}>
                    <Home />
                </React.Suspense>
            } />
            <Route path="edit/:templateId" element={
                <React.Suspense fallback={<Spinner />}>
                    <EditTemplate />
                </React.Suspense>
            } />
            <Route path="send/:templateId" element={
                <React.Suspense fallback={<Spinner />}>
                    <SendTemplate />
                </React.Suspense>
            } />
            <Route path="all-drafts" element={
                <React.Suspense fallback={<Spinner />}>
                    <AllTemplates />
                </React.Suspense>
            } />
            <Route path="history" element={
                <React.Suspense fallback={<Spinner />}>
                    <AllSendingHistory />
                </React.Suspense>
            } />

            <Route path=":messageId/versions/" element={
                <React.Suspense fallback={<Spinner />}>
                    <Versions />
                </React.Suspense>
            } />
        </Routes>
    )
}

export default MessagesApp;
