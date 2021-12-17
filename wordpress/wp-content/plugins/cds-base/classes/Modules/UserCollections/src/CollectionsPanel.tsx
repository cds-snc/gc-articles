import * as React from 'react';
import { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Spinner } from '../../Spinner/src/Spinner';
import { getData } from '../../Notify/src/NotifyPanel';

const CDS_VARS = window.CDS_VARS || {};

const requestHeaders = new Headers();
requestHeaders.append('X-WP-Nonce', CDS_VARS.rest_nonce);

interface Collection {
  siteurl: string;
  domain: string;
  blogname: string;
  path: string;
  current?: boolean;
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
    const dashboardText = __('Dashboard', 'cds-snc');
    const websiteText = __('Visit', 'cds-snc');
    const isCurrent = collection.current;

    const renderName = (blogname, current = false) => {
      if (current) {
        return <strong>{ blogname } ({__('current', 'cds-snc')})</strong>
      }
      return blogname;
    }

    return (
      <tr key={`row-${index}`} className={`row-${index}`}>
        <td className="name">{renderName(collection.blogname, collection.current)}</td>
        <td className="website">
          <a
            aria-label={`${websiteText} ${collection.blogname} `}
            href={collection.siteurl}
          >
            {websiteText}
          </a>
        </td>
        <td className="admin">
          <a
            aria-label={`${dashboardText} ${collection.blogname} `}
            href={`//${collection.domain}${collection.path}wp-admin`}
          >
            {dashboardText}
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
            <strong className="collection-website">
              {__('Website', 'cds-snc')}
            </strong>
          </th>
          <th>
            <strong className="collection-admin">
              {__('Admin', 'cds-snc')}
            </strong>
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
      const response = await getData('usercollection/collections');
      setIsLoading(false);
      const collections = Object.values(response);
      if (collections.length >= 1) {
        setCollections(Object.values(collections));
      }
    };

    fetchData();
  }, []);

  return (
    <div id="collection-panel-container">
      <div>
        <Collections collections={collections} isLoading={isLoading} />
      </div>
    </div>
  );
};
