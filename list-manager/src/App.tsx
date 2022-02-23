import React, { Suspense } from 'react'
import './App.css';
import { ListViewTable } from './components/ListViewTable';
import { UpdateList } from './components/UpdateList';
import { CreateList } from './components/CreateList';
import { DeleteList } from './components/DeleteList';
import { ResetList } from './components/ResetList';
import { Provider } from 'use-http';
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { Spinner } from './components/Spinner';

// @todo - lazy load components for routes
// https://stackblitz.com/github/remix-run/react-router/tree/main/examples/lazy-loading?file=src/App.tsx

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
          <Routes>
            <Route path="/" element={<ListViewTable />} />
            <Route path="/list/create" element={<CreateList />} />
            <Route path="/list/:listId" element={<UpdateList />} />
            <Route path="/list/:listId/delete" element={<DeleteList />} />
            <Route path="/list/:listId/reset" element={<ResetList />} />
          </Routes>
        </Suspense>
      </Provider>
    </BrowserRouter>
  )
}

export default App;