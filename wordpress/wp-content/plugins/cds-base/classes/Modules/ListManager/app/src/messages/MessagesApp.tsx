import * as React from 'react';
import { Routes, Route } from "react-router-dom";
import { Main } from "./components/Main";
import { Spinner } from '../common/Spinner';
const EditTemplate = React.lazy(() => import("./editor/EditTemplate"));

// route http://localhost:3000/#/messages/123/edit/123
const MessagesApp = () => {
    return (
        <Routes>
            <Route path="/" element={<Main />} />
            <Route path=":serviceId/edit/:templateId" element={
                <React.Suspense fallback={<Spinner />}>
                    <EditTemplate />
                </React.Suspense>
            } />
        </Routes>
    )
}

export default MessagesApp;