import { useState, useCallback } from 'react'
import useFetch from 'use-http';
import { useParams } from "react-router-dom";
import { SubmitHandler } from "react-hook-form";
import { Navigate } from "react-router-dom";

import { List, ListId } from "../types";
import { useList } from "../store/ListContext";
import { ListForm } from "./ListForm";
import { useListFetch } from '../store/UseListFetch';

export const UpdateList = () => {
  const { request, cache, response } = useFetch({ data: [] });
  const [responseData, setResponseData] = useState<ListId>({ id: null });
  const { state } = useList();

  useListFetch();

  let params = useParams();
  const listId = params?.listId

  const onSubmit: SubmitHandler<List> = data => updateList(listId, data);

  const updateList = useCallback(async (listId: string | undefined, formData: List) => {
    // remove id from payload
    const { id, subscriber_count, active, ...updateData } = formData;

    await request.put(`list/${listId}`, updateData)

    if (response.ok) {
      cache.clear();
      setResponseData({ id: id })
    }

    return {}
  }, [response, request, cache]);

  const list = state.lists.filter((list: any) => {
    return list.id === listId
  })[0];

  if (responseData.id) {
    return <Navigate to="/" replace={false} />
  }

  return list ? <ListForm formData={list} handler={onSubmit} /> : null
}

export default UpdateList;