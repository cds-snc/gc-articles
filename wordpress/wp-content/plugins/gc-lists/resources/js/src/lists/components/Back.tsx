import * as React from 'react';
import { Link } from "react-router-dom";
import { __ } from "@wordpress/i18n";

export const Back = () => {
    return <Link className="button action" to={{ pathname: `/lists` }}>{__('Go back', 'cds-snc')}</Link>
}
