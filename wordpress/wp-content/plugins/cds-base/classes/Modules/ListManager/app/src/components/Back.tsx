import { Link } from "react-router-dom";
import { useParams } from "react-router-dom";
export const Back = () => {
    const params = useParams();
    const serviceId = params?.serviceId;
    return <Link className="button action" to={{ pathname: `/service/${serviceId}` }}>back</Link>
}