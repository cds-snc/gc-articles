import * as React from 'react';
import { HashRouter, Routes, Route } from "react-router-dom";
import ListsApp from './lists/ListsApp';
import MessagesApp from './messages/MessagesApp';
import { ServiceData, User } from "./types"
import { NotFound } from './lists/components/NotFound';
import { ListProvider } from "./store/ListContext"
import { Provider } from 'use-http';

let endpoint = "/wp-json/list-manager";

if (process.env.NODE_ENV === "development") {
    endpoint = "http://localhost:3000";
}

const App = ({ serviceData, user }: { serviceData: ServiceData, user: User }) => {
    const options = {
        interceptors: {
            request: async ({ options }: { options: any }) => {
                if (window?.CDS_VARS?.rest_nonce) {
                    options.headers["X-WP-Nonce"] = window.CDS_VARS.rest_nonce;
                }
                return options
            },
        }
    }

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
        <Provider url={endpoint} options={options}>
            <HashRouter>
                <ListProvider serviceData={serviceData} user={user}>
                    <Routes>
                        <Route path="/messages/*" element={<MessagesApp />} />
                        <Route path="/service/*" element={<ListsApp />} />
                        <Route path="*" element={<NotFound />} />
                    </Routes>
                </ListProvider>
            </HashRouter>
        </Provider >
    )
}

export default App;