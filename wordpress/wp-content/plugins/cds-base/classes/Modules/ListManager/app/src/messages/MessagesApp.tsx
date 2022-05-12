import * as React from 'react';
import { Routes, Route } from "react-router-dom";
import { Spinner } from '../common/Spinner';
const ListTemplates = React.lazy(() => import("./components/ListTemplates"));
const EditTemplate = React.lazy(() => import("./components/EditTemplate"));

// route http://localhost:3000/#/messages/123/edit/123
const MessagesApp = () => {
    return (
        <Routes>
            <Route path="/" element={
                <React.Suspense fallback={<Spinner />}>
                    <ListTemplates />
                </React.Suspense>
            } />
            <Route path=":serviceId/edit/:templateId" element={
                <React.Suspense fallback={<Spinner />}>
                    <EditTemplate />
                </React.Suspense>
            } />
        </Routes>
    )
}

export default MessagesApp;