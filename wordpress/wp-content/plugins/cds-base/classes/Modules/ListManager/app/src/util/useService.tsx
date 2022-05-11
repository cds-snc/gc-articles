import { useParams } from "react-router-dom";

export const useService = () => {
    const params = useParams();
    const serviceId = params?.serviceId;
    const listId = params?.listId;
    const type = params?.type;
    return { serviceId, listId, type };
}