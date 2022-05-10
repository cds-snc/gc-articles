import * as React from 'react';
import { Suspense } from 'react'
import { Provider } from 'use-http';
import { Routes, Route } from "react-router-dom";
import { Services } from './components/Services';
import { Spinner } from '../common/Spinner';
import './App.css';
const Service = React.lazy(() => import("./components/Service"));
const UpdateList = React.lazy(() => import("./components/UpdateList"));
const CreateList = React.lazy(() => import("./components/CreateList"));
const UploadList = React.lazy(() => import("./components/UploadList"));

let endpoint = "/wp-json/list-manager";

if (process.env.NODE_ENV === "development") {
  endpoint = "http://localhost:3000";
}

const ListsApp = () => {
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
    <Provider url={endpoint} options={options}>
      <Suspense fallback={<Spinner />}>
          <Routes>
            <Route path="/" element={<Services />} />
            <Route path=":serviceId" element={
              <React.Suspense fallback={<Spinner />}>
                <Service />
              </React.Suspense>
            }
            />
            <Route path=":serviceId/list/create" element={
              <React.Suspense fallback={<Spinner />}>
                <CreateList />
              </React.Suspense>
            } />
            <Route path=":serviceId/list/:listId/update" element={
              <React.Suspense fallback={<Spinner />}>
                <UpdateList />
              </React.Suspense>
            } />
            <Route path=":serviceId/list/:listId/upload/:type" element={
              <React.Suspense fallback={<Spinner />}>
                <UploadList />
              </React.Suspense>
            } />
          </Routes>
       
      </Suspense>
    </Provider>

  )
}

export default ListsApp;