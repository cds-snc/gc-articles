import React, { Suspense } from 'react'
import { Provider } from 'use-http';
import { BrowserRouter, Routes, Route } from "react-router-dom";

import { Spinner } from './components/Spinner';
import { ListProvider } from "./store/ListContext"
import { ListViewTable } from './components/ListViewTable';
import './App.css';
const UpdateList = React.lazy(() => import("./components/UpdateList"));
const CreateList = React.lazy(() => import("./components/CreateList"));
const UploadList = React.lazy(() => import("./components/UploadList"));

// @todo 
// group lists by Service Ids
// https://react-table.tanstack.com/docs/examples/grouping-column

// get subscriber counts for lists + display 

const App = () => {
  const options = {
    interceptors: {
      request: async ({ options }: { options: any }) => {
        options.headers.Authorization = `c806e63f-ca53-415f-866d-64e442531a39`
        return options
      },
    }
  }

  return (
    <BrowserRouter>
      <Provider url="http://localhost:8000" options={options}>
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
              <Route path="/upload" element={
                <React.Suspense fallback={<Spinner />}>
                  <UploadList />
                </React.Suspense>
              } />
            </Routes>


          </ListProvider>
        </Suspense>
      </Provider>
    </BrowserRouter >
  )
}

export default App;