import * as React from 'react';
import { useState, useEffect } from 'react';
import { useService } from '../../util/useService';
import useFetch from 'use-http';

export const Versions = ({ }: {}) => {

    const [loading, setLoading] = useState(false);
    const [data, setData] = useState([]);
    const { serviceId } = useService();
    const { request, response } = useFetch({ data: [] })

    useEffect(() => {
        const getTemplateVersions = async () => {
            setLoading(true);
            await request.get(`/messages/sent}`);
            if (response.ok) {
                console.log(response)
                setData(await response.json());
                setLoading(false)
            }
        }
        getTemplateVersions();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    return (
        <div>
            Versions
        </div>
    )
}

export default Versions;