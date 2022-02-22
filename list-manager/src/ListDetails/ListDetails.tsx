import React, { useState, useCallback } from 'react'
import useFetch from 'use-http';
import { useParams } from "react-router-dom";
import { ListForm } from "../ListForm/ListForm";
import { SubmitHandler } from "react-hook-form";
import { Inputs } from "../types";
/*
List Form
{
  "name": "string",
  "language": "string",
  "service_id": "string",
  "subscribe_email_template_id": "3fa85f64-5717-4562-b3fc-2c963f66afa6",
  "unsubscribe_email_template_id": "3fa85f64-5717-4562-b3fc-2c963f66afa6",
  "subscribe_phone_template_id": "3fa85f64-5717-4562-b3fc-2c963f66afa6",
  "unsubscribe_phone_template_id": "3fa85f64-5717-4562-b3fc-2c963f66afa6",
  "subscribe_redirect_url": "string",
  "confirm_redirect_url": "string",
  "unsubscribe_redirect_url": "string"
}
*/

// create
// reset
// delete

export const ListDetails = () => {

  
  const { put, response } = useFetch({ data: [] })
  const [data, setData] = useState({ id: null })

  const updateList = useCallback(async (formData: Inputs) => {
    await put(`list/${formData.id}`, formData)

    if (response.ok) {
      setData(await response.json())
    }

    return {}


  }, [response, put]);

  const onSubmit: SubmitHandler<Inputs> = data => updateList(data);

  let params = useParams();
  return <div>{params.listId} <ListForm handler={onSubmit} /></div>
}