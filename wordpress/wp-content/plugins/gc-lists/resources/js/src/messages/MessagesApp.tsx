import * as React from 'react';
import { Routes, Route } from "react-router-dom";
import { Spinner } from './components';
import Home from './views/Home';
import EditMessage from './views/EditMessage';
import SendMessage from './views/SendMessage';
import AllDrafts from './views/AllDrafts';
import AllSendingHistory from './views/AllSendingHistory';
import Versions from './views/Versions';


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
