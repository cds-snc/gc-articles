import { useParams } from "react-router-dom";
import { useList } from './listContext';

export const useService = () => {
    const { state: { serviceData } } = useList();
    const params = useParams();
    const serviceId = serviceData?.service_id;
    const subscribeTemplate = serviceData?.subscribeTemplate;
    const listId = params?.listId;
    const type = params?.type;
    return { serviceId, subscribeTemplate, listId, type };
}
