/**
 * External dependencies
 */
import * as React from 'react';
import { HashRouter, Routes, Route } from "react-router-dom";
import { Provider } from 'use-http';

/**
 * Internal dependencies
 */
import { ServiceData, User } from "./types"
import { ListProvider } from "./store";
import MessagesApp from './messages/MessagesApp';
import { NotFound } from './lists/components/NotFound';
import ListsApp from './lists/ListsApp';

const REST_URL = window?.CDS_VARS?.rest_url;
const endpoint = `${REST_URL}gc-lists`;

const App = ({ serviceData, user, baseUrl }: { serviceData: ServiceData, user: User, baseUrl: string | null }) => {
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
                <ListProvider serviceData={serviceData} user={user} config={{ listManagerApiPrefix: `${baseUrl}/wp-json/list-manager` }}>
                    <Routes>
                        <Route path="/messages/*" element={<MessagesApp />} />
                        <Route path="/lists/*" element={<ListsApp />} />
                        <Route path="*" element={<NotFound />} />
                    </Routes>
                </ListProvider>
            </HashRouter>
        </Provider >
    )
}

export default App;
