import * as React from 'react';
import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Spinner } from '../../Spinner/src/Spinner';
import { getData } from 'util/fetch';

interface TableInfo {
  name: string;
}

const DBInsights = ({
  tables,
  isLoading,
}: {
  tables: TableInfo[];
  isLoading: boolean;
}) => {
  if (isLoading) return <Spinner />;

  if (tables && tables.length < 1) {
    return null;
  }

  const rows = tables.map((table, index) => {

    return (
      <div key={index}>
        {table.name}
      </div>
    );
  });
  return (
    <div className="wp-list-table">
      <h4>
        <span style={{ color: "red" }} className="dashicons dashicons-warning"></span>
        {__(' Tables exist from deleted collections', 'cds-snc')}
      </h4>
      <div className="faded-edge" style={{ paddingLeft: 30, height: 200, overflowY: "scroll" }}>
        {rows}
      </div>
    </div>
  );
};

export const DBInsightsPanel = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [tables, setTables] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await getData(`maintenance/v1/deleted-site-tables`);
        setIsLoading(false);
        if (response && response?.tables) {
          setTables(response.tables);
        }
      } catch (e) {
        setIsLoading(false);
      }
    };

    fetchData();
  }, []);

  return (
    <div id="db-insights-panel-container">
      <div>
        <DBInsights tables={tables} isLoading={isLoading} />
      </div>
    </div>
  );
};
