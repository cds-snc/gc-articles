import React, { useCallback, useEffect, useState } from 'react'
import useFetch from 'use-http';
import { Navigate, useParams } from 'react-router-dom';

export const ResetList = () => {
    const { request, response } = useFetch({ data: [] })
    const [data, setData] = useState({ reset: false })
    
    const resetList = useCallback(async (id) => {
        await request.put(`/list/${id}/reset`)

        if (response.ok) {
            setData({reset:true})
        }

        return {}

    }, [request, response]);

    let params = useParams();
    const listId = params?.listId

    useEffect(() => {
        resetList(listId)
    }, [resetList, listId]);

    return data.reset ? <Navigate to="/" replace={true} /> : <div>There was an error</div>
}