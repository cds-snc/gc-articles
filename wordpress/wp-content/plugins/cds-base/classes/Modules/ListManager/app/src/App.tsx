import * as React from 'react';
import { HashRouter, Routes, Route } from "react-router-dom";
import ListsApp from './lists/ListsApp';
import MessagesApp from './messages/MessagesApp';
import { ServiceData, User } from "./types"
import { NotFound } from './lists/components/NotFound';
import { ListProvider } from "./store/ListContext"

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
            <ListProvider serviceData={serviceData} user={user}>
                <Routes>
                    <Route path="/messages/*" element={<MessagesApp />} />
                    <Route path="/service/*" element={<ListsApp />} />
                    <Route path="*" element={<NotFound />} />
                </Routes>
            </ListProvider>
        </HashRouter>
    )
}

export default App;