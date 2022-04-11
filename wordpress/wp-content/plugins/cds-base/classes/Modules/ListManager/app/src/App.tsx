import * as React from 'react';
import { Suspense } from 'react'
import { Provider } from 'use-http';
import { HashRouter, Routes, Route } from "react-router-dom";

import { Spinner } from './components/Spinner';
import { ListProvider } from "./store/ListContext"
import { Services } from './components/Services';
import { NotFound } from './components/NotFound';
import { ServiceData } from "./types";
import './App.css';
const Service = React.lazy(() => import("./components/Service"));
const UpdateList = React.lazy(() => import("./components/UpdateList"));
const CreateList = React.lazy(() => import("./components/CreateList"));
const UploadList = React.lazy(() => import("./components/UploadList"));

let endpoint = "/wp-json/list-manager";

if(process.env.NODE_ENV === "development"){
  endpoint = "http://localhost:3000";
}

const App = ({ serviceData }: { serviceData: ServiceData }) => {
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

  return (
    <HashRouter>
      <Provider url={endpoint} options={options}>
        <Suspense fallback={<Spinner />}>
          <ListProvider serviceData={serviceData}>
            <Routes>
              <Route path="/" element={<Services />} />
              <Route path="/service/:serviceId" element={
                <React.Suspense fallback={<Spinner />}>
                  <Service />
                </React.Suspense>
              }
              />
              <Route path="/service/:serviceId/list/create" element={
                <React.Suspense fallback={<Spinner />}>
                  <CreateList />
                </React.Suspense>
              } />
              <Route path="/service/:serviceId/list/:listId/update" element={
                <React.Suspense fallback={<Spinner />}>
                  <UpdateList />
                </React.Suspense>
              } />
              <Route path="/service/:serviceId/list/:listId/upload/:type" element={
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