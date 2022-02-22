import React, { Suspense } from 'react'
import './App.css';
import { ListView } from './ListView/Table';
import { ListDetails } from './ListDetails/ListDetails';
import { ListCreate } from './ListCreate/ListCreate';
import { DeleteList } from './components/DeleteList';
import { ResetList } from './components/ResetList';
import { Provider } from 'use-http';
import { BrowserRouter, Routes, Route} from "react-router-dom";

// https://codesandbox.io/s/github/ggascoigne/react-table-example

function App() {
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
        <Suspense fallback='Loading...'>
          <Routes>
            <Route path="/" element={<ListView />} />
            <Route path="/list/create" element={<ListCreate />} />
            <Route path="/list/:listId" element={<ListDetails />} />
            <Route path="/list/:listId/delete" element={<DeleteList />} />
            <Route path="/list/:listId/reset" element={<ResetList />} />
          </Routes>
        </Suspense>
      </Provider>
    </BrowserRouter>
  )
}

export default App;