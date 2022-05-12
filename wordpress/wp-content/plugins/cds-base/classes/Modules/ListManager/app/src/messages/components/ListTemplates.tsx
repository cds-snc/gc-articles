import * as React from 'react';
import { useState, useEffect } from 'react';
import { v4 as uuidv4 } from 'uuid';
import useTemplateApi from '../../store/useTemplateApi';
import { Link } from "react-router-dom";
import { useService } from '../../util/useService';
export const ListTemplates = () => {
    const [templates, setTemplates] = useState<string[]>([]);
    const { getTemplates } = useTemplateApi();
    const { serviceId } = useService();

    useEffect(() => {
        const fetchTempates = async () => {
            setTemplates(await getTemplates());
        }
        fetchTempates();
    }, [getTemplates])

    return (
        <>
            <Link to={{ pathname: `/messages/${serviceId}/edit/${uuidv4()}` }}>Create Template</Link>
            {templates.map((templateId) => {
                return <div key={templateId}><Link to={{ pathname: `/messages/${serviceId}/edit/${templateId}` }}>{templateId}</Link></div>
            })}
        </>
    )
}

export default ListTemplates;