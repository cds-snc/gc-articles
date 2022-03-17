import * as React from 'react';
import { Link } from "react-router-dom";
import { useParams } from "react-router-dom";
import { __ } from "@wordpress/i18n";

export const Back = () => {
    const params = useParams();
    const serviceId = params?.serviceId;
    return <Link className="button action" to={{ pathname: `/service/${serviceId}` }}>{__('Go back', 'cds-snc')}</Link>
}
