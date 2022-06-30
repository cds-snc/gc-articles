/**
 * External dependencies
 */
import * as React from 'react';
import ReactDOM from 'react-dom';

/**
 * Internal dependencies
 */
import './lists/index.css';
import App from './App';
import { ServiceData, User, NotifyList } from "./types";
declare global {
  interface Window {
    CDS_LIST_MANAGER: { endpoint: string },
    renderListManager: (el: string) => void,
    CDS_VARS: {
      rest_url?: string;
      rest_nonce?: string;
      notify_list_ids?: NotifyList[];
    }
  }
}

const renderApp = () => {
  if (document.getElementById("list-manager-app")) {

    const container = document.getElementById('list-manager-app')!;

    if (container) {
      let data = container.getAttribute("data-ids");
      let user = container.getAttribute("data-user");
      let baseUrl = container.getAttribute('data-base-url');

      if (data && user) {
        data = JSON.parse(data);
        const serviceData = data as unknown as ServiceData;

        user = JSON.parse(user);
        const userData = user as unknown as User;

        ReactDOM.render(
          <React.StrictMode>
            <App serviceData={serviceData} user={userData} baseUrl={baseUrl} />
          </React.StrictMode>
          , container);
      }
    }
  }
}

renderApp();
