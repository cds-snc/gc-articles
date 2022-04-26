import * as React from 'react';
import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Spinner } from '../../Spinner/src/Spinner';
import { getData } from 'util/fetch';

interface ActivityInfo {
  name: string;
  post: string;
  page: string;
}

const DBActivity = ({
  data,
  isLoading,
}: {
  data: ActivityInfo[];
  isLoading: boolean;
}) => {
  if (isLoading) return <Spinner />;

  if (data && data.length < 1) {
    return null;
  }

  const rows = data.map((item, index) => {

    return (
      <div style={{ marginBottom: "10px" }}>

        <table key={index} className="wp-list-table widefat">
          <thead>
            <tr><th colSpan={2}><strong>{item.name}</strong> </th></tr>
            <tr>
              <th><strong>{__("Latest Page", "cds-snc")}</strong></th>
              <th><strong>{__("Latest Article", "cds-snc")}</strong></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{item.page}</td>
              <td>{item.post}</td>
            </tr>
          </tbody>
        </table>
      </div >
    );
  });
  return (
    <div className="wp-list-table">
      <div className="faded-edge" style={{ paddingLeft: 0, height: 200, overflowY: "scroll" }}>
        {rows}
      </div>
    </div>
  );
};

export const DBActivityPanel = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [data, setData] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await getData(`maintenance/v1/site-activity`);
        setIsLoading(false);
        if (response) {
          setData(response);
        }
      } catch (e) {
        setIsLoading(false);
      }
    };

    fetchData();
  }, []);

  return (
    <div id="db-activity-panel-container">
      <div>
        <DBActivity data={data} isLoading={isLoading} />
      </div>
    </div>
  );
};
