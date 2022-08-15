import * as React from 'react';
import { Suspense } from 'react'
import { Routes, Route } from "react-router-dom";
import { Spinner } from '../common/Spinner';
import './App.css';

import { Service } from "./components/Service";
import { UpdateList } from "./components/UpdateList";
import { CreateList } from "./components/CreateList";
import { ChooseSubscribers } from "./components/ChooseSubscribers";
import { UploadList } from "./components/UploadList";

const ListsApp = () => {
  return (
    <Suspense fallback={<Spinner />}>
      <Routes>
        <Route path="/" element={
          <React.Suspense fallback={<Spinner />}>
            <Service />
          </React.Suspense>
        }
        />
        <Route path="/create" element={
          <React.Suspense fallback={<Spinner />}>
            <CreateList />
          </React.Suspense>
        } />
        <Route path="/choose-subscribers" element={
          <React.Suspense fallback={<Spinner />}>
            <ChooseSubscribers />
          </React.Suspense>
        } />
        <Route path="/:listId/update" element={
          <React.Suspense fallback={<Spinner />}>
            <UpdateList />
          </React.Suspense>
        } />
        <Route path="/:listId/upload/:type" element={
          <React.Suspense fallback={<Spinner />}>
            <UploadList />
          </React.Suspense>
        } />
      </Routes>
    </Suspense>
  )
}

export default ListsApp;
