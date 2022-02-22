import React, { useCallback, useEffect, useState } from 'react'
import useFetch from 'use-http';
import { Navigate, useParams } from 'react-router-dom';

export const DeleteList = () => {
    const { request, response } = useFetch({ data: [] })
    const [data, setData] = useState({ deleted: false })
    
    const deleteList = useCallback(async (id) => {
        console.log(id);
        await request.delete(`/list/${id}`)

        
        if (response.ok) {
            setData({deleted:true})
        }

        return {}

    }, [request, response]);

    let params = useParams();
    const listId = params?.listId

    useEffect(() => {
        deleteList(listId)
    }, [deleteList, listId]);

    return data.deleted ? <Navigate to="/" replace={true} /> : <div>There was an error</div>
}