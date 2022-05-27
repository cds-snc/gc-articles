import * as React from 'react';
import { useState, useEffect } from 'react';
import { useService } from '../../util/useService';
import { useParams } from "react-router-dom";
import useFetch from 'use-http';
import { MessagePreview } from './MessagePreview';

export const Versions = ({ }: {}) => {

    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);
    const { serviceId } = useService();
    const { request, response } = useFetch({ data: [] })
    const params = useParams();

    useEffect(() => {
        const getTemplateVersions = async () => {
            setLoading(true);
            const messageId = params?.messageId;
            await request.get(`/messages/${messageId}/versions`);
            if (response.ok) {
                console.log(await response.json())
                setData(await response.json());
                setLoading(false)
            }
        }
        getTemplateVersions();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    return (
        <div>
            { data?.length > 0 ? data.map((item) => {
                // @ts-ignore
                return <MessagePreview content={item.body} subject={item.subject} />
            }) : null }
        </div>
    )
}

export default Versions;