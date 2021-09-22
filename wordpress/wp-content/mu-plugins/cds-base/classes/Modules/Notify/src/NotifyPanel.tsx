import * as React from 'react';
import { useState, useEffect } from 'react';
import { NotifySendTemplateHandler } from './NotifySendTemplateHandler';
import { __ } from '@wordpress/i18n';
import { Spinner } from '../../Spinner/src/Spinner';
import { List } from './Types';

const CDS_VARS = window.CDS_VARS || {};

const requestHeaders = new Headers();
requestHeaders.append('X-WP-Nonce', CDS_VARS.rest_nonce);

export const slugify = (...args: (string | number)[]): string => {
  const value = args.join(' ');

  return value
    .normalize('NFD') // split an accented letter in the base letter and the acent
    .replace(/[\u0300-\u036f]/g, '') // remove all previously split accents
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9 ]/g, '') // remove all chars not letters, numbers and spaces (to be replaced)
    .replace(/\s+/g, '-'); // separator
};

export const getData = async (endpoint: string) => {
  const response = await fetch(`${CDS_VARS.rest_url}${endpoint}`, {
    method: 'GET',
    headers: requestHeaders,
    mode: 'cors',
    cache: 'default',
  });

  if (!response.ok) {
    throw new Error(`HTTP error! status: ${response.status}`);
  }
  return await response.json();
};

export const findListById = (list: List[], id: string) => {
  return list.find((l: List) => l.list_id === id);
};

const matchCountToList = (listCounts: List[]) => {
  const listDetails = CDS_VARS.notify_list_ids;

  const data: List[] = [];

  listDetails.forEach((list: List) => {
    const obj = findListById(listCounts, list.id);
    let subscriber_count = 0;
    if (obj) {
      subscriber_count = obj.subscriber_count;
    }

    data.push({ ...list, list_id: list.id, subscriber_count });
  });

  return data;
};

const NotifyIcon = () => {
  return (
    <svg viewBox="0 0 244 150" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path
        d="M203.675 70.7087C203.464 70.7087 203.148 70.7087 202.937 70.7087C205.258 64.9043 206.525 58.4666 206.525 51.8179C206.525 23.2178 183.307 0 154.707 0C129.378 0 108.271 18.1521 103.839 42.2142C98.245 39.3647 91.808 37.6761 85.054 37.6761C65.002 37.6761 48.222 51.9234 44.422 70.9198C42.945 70.7087 41.362 70.6032 39.884 70.6032C18.039 70.6032 0.309021 88.3332 0.309021 110.179C0.309021 132.025 18.039 149.755 39.884 149.755H203.781C225.627 149.755 243.357 132.025 243.357 110.179C243.251 88.4387 225.521 70.7087 203.675 70.7087Z"
        fill="#B2E3FF"
      />
      <path
        d="M100.777 70.2871H174.23C175.707 70.2871 176.974 71.5535 176.974 73.031V119.361C176.974 120.839 175.707 122.105 174.23 122.105H100.777C99.3 122.105 98.033 120.839 98.033 119.361V73.031C98.033 71.448 99.3 70.2871 100.777 70.2871ZM128.85 93.9271L107.109 116.617H167.687L146.052 94.0326L139.087 98.4651C138.242 98.9928 137.081 99.0983 136.132 98.4651L128.85 93.9271ZM103.521 112.396L124.1 90.9721L103.521 77.9912V112.396ZM150.695 91.0776L171.486 112.712V77.8857L150.695 91.0776ZM110.275 75.775L137.503 92.9772L164.732 75.775H110.275Z"
        fill="#26374A"
      />
      <path
        d="M86.745 85.2744H72.076C70.915 85.2744 69.965 86.2242 69.965 87.3851C69.965 88.546 70.915 89.4958 72.076 89.4958H86.745C87.906 89.4958 88.856 88.546 88.856 87.3851C88.856 86.2242 87.906 85.2744 86.745 85.2744Z"
        fill="#26374A"
      />
      <path
        d="M86.742 98.4668H64.896C63.735 98.4668 62.785 99.4166 62.785 100.578C62.785 101.738 63.735 102.688 64.896 102.688H86.742C87.903 102.688 88.852 101.738 88.852 100.578C88.852 99.3111 87.903 98.4668 86.742 98.4668Z"
        fill="#26374A"
      />
      <path
        d="M86.742 112.186H57.509C56.348 112.186 55.398 113.135 55.398 114.296C55.398 115.457 56.348 116.407 57.509 116.407H86.742C87.903 116.407 88.853 115.457 88.853 114.296C88.853 113.135 87.903 112.186 86.742 112.186Z"
        fill="#26374A"
      />
    </svg>
  );
};

const NotifyLists = ({
  listCounts,
  isLoading,
}: {
  listCounts: List[];
  isLoading: boolean;
}) => {
  if (isLoading) return <Spinner />;

  if (listCounts && listCounts.length < 1) {
    return null;
  }

  const rows = listCounts.map((list) => {
    const id = slugify(list.label);
    return (
      <tr>
        <td className={`label-${id}`}>{list.label}</td>
        <td className={`subscriber-count-${id}`}>{list.subscriber_count}</td>
      </tr>
    );
  });
  return (
    <table className="wp-list-table widefat">
      <thead>
        <tr>
          <th>
            <strong>{__('List Name', 'cds-snc')}</strong>
          </th>
          <th>
            <strong>{__('Subscriber Count', 'cds-snc')}</strong>
          </th>
        </tr>
      </thead>
      <tbody>{rows}</tbody>
    </table>
  );
};

export const NotifyPanel = ({ sendTemplateLink = false }) => {
  const [isLoading, setIsLoading] = useState(true);
  const [listCounts, setListCounts] = useState([]);

  useEffect(() => {
    const fetchData = async () => {
      const response = await getData('wp-notify/v1/list_counts');
      setIsLoading(false);
      if (response.length >= 1) {
        setListCounts(await matchCountToList(response));
      }
    };

    fetchData();
  }, []);

  const text = __('Send Template', 'cds-snc');

  return (
    <div>
      <div
        id="notify-panel-container"
        style={{
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          marginBottom: '20px',
        }}
      >
        {sendTemplateLink && (
          <>
            <div style={{ fontSize: '1.4rem' }}>
              <a
                style={{ marginRight: '20px' }}
                href="/wp-admin/admin.php?page=cds_notify_send"
              >
                {text}
              </a>
            </div>

            <div style={{ width: 90 }}>
              <NotifyIcon />
            </div>
          </>
        )}
      </div>
      <div>
        <NotifyLists listCounts={listCounts} isLoading={isLoading} />
      </div>
      {listCounts && listCounts.length >= 1 && (
        <NotifySendTemplateHandler listCounts={listCounts} />
      )}
    </div>
  );
};
