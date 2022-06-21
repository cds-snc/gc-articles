import * as React from 'react';
import { Routes, Route } from "react-router-dom";
import { Spinner } from './components';
const Home = React.lazy(() => import("./views/Home"));
const EditMessage = React.lazy(() => import("./views/EditMessage"));
const SendMessage = React.lazy(() => import("./views/SendMessage"));
const AllDrafts = React.lazy(() => import("./views/AllDrafts"));
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
                    <EditMessage />
                </React.Suspense>
            } />
            <Route path="send/:templateId" element={
                <React.Suspense fallback={<Spinner />}>
                    <SendMessage />
                </React.Suspense>
            } />
            <Route path="all-drafts" element={
                <React.Suspense fallback={<Spinner />}>
                    <AllDrafts />
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
