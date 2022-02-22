import React, { useState, useCallback } from 'react'
import useFetch from 'use-http';
import { useParams } from "react-router-dom";
import { ListForm } from "../ListForm/ListForm";
import { SubmitHandler } from "react-hook-form";
import { Inputs } from "../types";

// create
// reset
// delete

export const ListDetails = () => {


  const { put, response } = useFetch({ data: [] })
  const [data, setData] = useState({ id: null })

  const updateList = useCallback(async (listId: string | undefined, formData: Inputs) => {
    
    await put(`list/${listId}`, formData)

    if (response.ok) {
      setData(await response.json())
    }

    return {}


  }, [response, put]);

  let params = useParams();
  const listId = params?.listId
  const onSubmit: SubmitHandler<Inputs> = data => updateList(listId, data);
  return (<ListForm handler={onSubmit} />)
}