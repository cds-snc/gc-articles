import * as React from 'react';
import { Link } from "react-router-dom";
import { __ } from "@wordpress/i18n";
import { useService } from '../util/useService';

export const Back = () => {
    const { serviceId } = useService();
    return <Link className="button action" to={{ pathname: `/service/${serviceId}` }}>{__('Go back', 'cds-snc')}</Link>
}
