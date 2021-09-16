import { useState, useEffect } from 'react';
import { __ } from "@wordpress/i18n";
import { getData } from './dashboard';

CDS_VARS = window.CDS_VARS;

const requestHeaders = new Headers();
requestHeaders.append('X-WP-Nonce', CDS_VARS.rest_nonce);

const Logins = ({ logins, isLoading }) => {
    if (isLoading) return (
      <div style={{ display: "flex", justifyContent: "center" }}>
          <div className="lds-ellipsis">
              <div></div>
              <div></div>
              <div></div>
              <div></div>
          </div>
      </div>
    )

    if (logins < 1) {
        return null
    }

    const rows = logins.map((login) => {
        const date = new Date(login.time_login);

        return <tr><td>{date.toLocaleString()}</td><td>{login.user_agent}</td></tr>
    })
    return (
      <table className="wp-list-table widefat">
          <thead>
          <tr>
              <th><strong>{__("Date", "cds-snc")}</strong></th>
              <th><strong>{__("User agent", "cds-snc")}</strong></th>
          </tr>
          </thead>
          <tbody>
          {rows}
          </tbody>
      </table>)
}

export const LoginsPanel = () => {
    const [isLoading, setIsLoading] = useState(true);
    const [logins, setLogins] = useState('');

    useEffect(async () => {
        const response = await getData("user/logins");
        setIsLoading(false)
        if (response.length >= 1) {
            setLogins(response);
        }
    }, [])

    const text = __("Recent Logins", "cds-snc");

    return (
      <div id="logins-panel-container">
          <div>
              <Logins logins={logins} isLoading={isLoading} />
          </div>
      </div >
    )
}
