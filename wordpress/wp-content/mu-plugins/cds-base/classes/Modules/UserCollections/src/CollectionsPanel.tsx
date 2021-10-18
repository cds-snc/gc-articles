import * as React from 'react';
import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Spinner } from '../../Spinner/src/Spinner';
import { getData } from '../../Notify/src/NotifyPanel';

const CDS_VARS = window.CDS_VARS || {};

const requestHeaders = new Headers();
requestHeaders.append('X-WP-Nonce', CDS_VARS.rest_nonce);

interface Collection {
  siteurl: string,
  domain: string;
  blogname: string;
  path: string;
}

const Collections = ({
  collections,
  isLoading,
}: {
  collections: Collection[];
  isLoading: boolean;
}) => {
  if (isLoading) return <Spinner />;

  if (collections && collections.length < 1) {
    return null;
  }

  const rows = collections.map((collection, index) => {
    return (
      <tr className={`row-${index}`}>
        <td className="name">{collection.blogname}</td>
        <td className="website">
          <a href={collection.siteurl}>{__('Visit', 'cds-snc')}</a>
        </td>
        <td className="admin">
          <a href={`//${collection.domain}${collection.path}wp-admin`}>
            {__('Dashboard', 'cds-snc')}
          </a>
        </td>
      </tr>
    );
  });

  return (
    <table className="wp-list-table widefat">
      <thead>
        <tr>
          <th>
            <strong className="collection-name">{__('Name', 'cds-snc')}</strong>
          </th>
          <th>
            <strong className="collection-website">{__('Website', 'cds-snc')}</strong>
          </th>
          <th>
            <strong className="collection-admin">{__('Admin', 'cds-snc')}</strong>
          </th>
        </tr>
      </thead>
      <tbody>{rows}</tbody>
    </table>
  );
};

export const CollectionsPanel = () => {
  const [isLoading, setIsLoading] = useState(true);
  const [collections, setCollections] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      const response = await getData('user/collections');
      setIsLoading(false);
      const collections = Object.values(response);
      if (collections.length >= 1) {
        setCollections(Object.values(collections));
      }
    };

    fetchData();
  }, []);

  return (
    <div id="logins-panel-container">
      <div>
        <Collections collections={collections} isLoading={isLoading} />
      </div>
    </div>
  );
};
