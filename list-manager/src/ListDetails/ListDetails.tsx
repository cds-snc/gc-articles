import React, { useState, useCallback, useEffect } from 'react'
import useFetch from 'use-http';
import { useParams } from "react-router-dom";
import { ListForm } from "../ListForm/ListForm";
import { SubmitHandler } from "react-hook-form";
import { Inputs } from "../types";

export const ListDetails = () => {
  const { request, response, loading } = useFetch({ data: [] })
  const [inputData, setInputData] = useState({ id: null })

  let params = useParams();
  const listId = params?.listId

  const onSubmit: SubmitHandler<Inputs> = data => updateList(listId, data);

  const updateList = useCallback(async (listId: string | undefined, formData: Inputs) => {

    const { id, ...updateData } = formData;

    await request.put(`list/${listId}`, updateData)

    if (response.ok) {
      //
    }

    return {}
  }, [response, request]);

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

  return inputData?.id ? <ListForm formData={inputData} handler={onSubmit} /> : null

}