import { useState, useCallback, useEffect } from 'react'
import useFetch from 'use-http';
import { useParams } from "react-router-dom";
import { ListForm } from "./ListForm";
import { SubmitHandler } from "react-hook-form";
import { List, ListId } from "../types";
import { Navigate } from "react-router-dom";

export const UpdateList = () => {
  const { request, cache, response } = useFetch({ data: [] })
  const [inputData, setInputData] = useState({ id: null })
  const [responseData, setResponseData] = useState<ListId>({ id: null })

  let params = useParams();
  const listId = params?.listId

  const onSubmit: SubmitHandler<List> = data => updateList(listId, data);

  const updateList = useCallback(async (listId: string | undefined, formData: List) => {

    // remove id from payload
    const { id, ...updateData } = formData;

    await request.put(`list/${listId}`, updateData)

    if (response.ok) {
      cache.clear();
      setResponseData({ id: id })
    }

    return {}
  }, [response, request, cache]);

  const loadData = useCallback(async () => {
    await request.get('/lists')

    if (response.ok) {
      const lists = await response.json()

      const data = lists.filter((list: any) => {
        return list.id === listId
      })

      setInputData(data[0])
    }

  }, [response, request, listId]);

  useEffect(() => { loadData() }, [loadData]) // componentDidMount

  if (responseData.id) {
    return <Navigate to="/" replace={true} />
  }

  return inputData?.id ? <ListForm formData={inputData} handler={onSubmit} /> : null
}

export default UpdateList;