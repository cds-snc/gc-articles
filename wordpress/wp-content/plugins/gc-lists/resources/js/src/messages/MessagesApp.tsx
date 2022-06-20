import * as React from 'react';
import { Routes, Route } from "react-router-dom";
import { Spinner } from './components';
const Home = React.lazy(() => import("./views/Home"));
const EditTemplate = React.lazy(() => import("./views/EditTemplate"));
const SendTemplate = React.lazy(() => import("./views/SendTemplate"));
const AllTemplates = React.lazy(() => import("./views/AllTemplates"));
const AllSendingHistory = React.lazy(() => import("./views/AllSendingHistory"));
const Versions = React.lazy(() => import("./views/Versions"));

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
