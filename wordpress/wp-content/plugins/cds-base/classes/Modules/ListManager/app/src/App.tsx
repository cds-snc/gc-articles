import * as React from 'react';
import { Suspense } from 'react'
import { Provider } from 'use-http';
import { HashRouter, Routes, Route } from "react-router-dom";

import { Spinner } from './components/Spinner';
import { ListProvider } from "./store/ListContext"
import { ListViewTable } from './components/ListViewTable';
import { NotFound } from './components/NotFound';
import './App.css';
const UpdateList = React.lazy(() => import("./components/UpdateList"));
const CreateList = React.lazy(() => import("./components/CreateList"));
const UploadList = React.lazy(() => import("./components/UploadList"));

const endpoint = "/wp-json/list-manager";

const App = () => {
  const options = {
    interceptors: {
      request: async ({ options }: { options: any }) => {
        options.headers["X-WP-Nonce"] = window.CDS_VARS.rest_nonce
        return options
      },
    }
  }

  return (
    <HashRouter>
      <Provider url={endpoint} options={options}>
        <Suspense fallback={<Spinner />}>
          <ListProvider>
            <Routes>
              <Route path="/" element={<ListViewTable />} />
              <Route path="/list/create" element={
                <React.Suspense fallback={<Spinner />}>
                  <CreateList />
                </React.Suspense>
              } />
              <Route path="/list/:listId" element={
                <React.Suspense fallback={<Spinner />}>
                  <UpdateList />
                </React.Suspense>
              } />
              <Route path="/upload/:listId" element={
                <React.Suspense fallback={<Spinner />}>
                  <UploadList />
                </React.Suspense>
              } />

              <Route path="*" element={<NotFound />} />
            </Routes>
          </ListProvider>
        </Suspense>
      </Provider>
    </HashRouter>
  )
}

export default App;