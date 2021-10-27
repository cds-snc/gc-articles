import * as React from 'react';
import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Spinner } from '../../Spinner/src/Spinner';
import { getData } from 'util/fetch';
interface Login {
  user_agent: string;
  time_login: string;
}

const Logins = ({
  logins,
  isLoading,
}: {
  logins: Login[];
  isLoading: boolean;
}) => {
  if (isLoading) return <Spinner />;

  if (logins && logins.length < 1) {
    return null;
  }

  const rows = logins.map((login) => {
    const date = new Date(login.time_login);

    return (
      <tr>
        <td>{date.toLocaleString()}</td>
        <td>{login.user_agent}</td>
      </tr>
    );
  });
  return (
    <table className="wp-list-table widefat">
      <thead>
        <tr>
          <th>
            <strong className="login-date">{__('Date', 'cds-snc')}</strong>
          </th>
          <th>
            <strong className="login-userAgent">{__('User agent', 'cds-snc')}</strong>
          </th>
        </tr>
      </thead>
      <tbody>{rows}</tbody>
    </table>
  );
};

export const LoginsPanel = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [logins, setLogins] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await getData('user/logins');
        setIsLoading(false);
        if (response.length >= 1) {
          setLogins(response);
        }
      } catch (e) {
        setIsLoading(false);
      }
    };

    fetchData();
  }, []);

  return (
    <div id="logins-panel-container">
      <div>
        <Logins logins={logins} isLoading={isLoading} />
      </div>
    </div>
  );
};
