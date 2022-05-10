import * as React from 'react';
import { HashRouter, Routes, Route } from "react-router-dom";
import ListsApp from './lists/ListsApp';
import MessagesApp from './messages/MessagesApp';
import { ServiceData, User } from "./lists/types"
import { NotFound } from './lists/components/NotFound';

const App = ({ serviceData, user }: { serviceData: ServiceData, user: User }) => {

    if (!user?.hasEmail && !user?.hasPhone) {
        return (
            <HashRouter>
                <Routes>
                    <Route path="*" element={<NotFound />} />
                </Routes>
            </HashRouter>
        )
    }

    return (
        <HashRouter>
            <Routes>
                <Route path="/messages/" element={<MessagesApp />} />
                <Route path="/service/*" element={<ListsApp serviceData={serviceData} user={user} />} />
                <Route path="*" element={<NotFound />} />
            </Routes>
        </HashRouter>
    )
}

export default App;