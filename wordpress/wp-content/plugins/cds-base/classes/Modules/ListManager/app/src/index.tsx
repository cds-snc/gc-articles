import * as React from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';
import App from './App';
import { List } from '../../../Notify/src/Types';
import { ServiceData } from "./types"
declare global {
  interface Window {
    CDS_LIST_MANAGER: { endpoint: string },
    renderListManager: (el: string) => void,
    CDS_VARS: {
      rest_url?: string;
      rest_nonce?: string;
      notify_list_ids?: List[];
    }
  }
}

const renderApp = () => {
  if (document.getElementById("list-manager-app")) {

    const container = document.getElementById('list-manager-app')!;
    const root = createRoot(container);

    if (container) {
      let data = container.getAttribute("data-ids");
      if (data) {
        data = JSON.parse(data);
        const serviceData = data as unknown as ServiceData;

        root.render(
          <React.StrictMode>
            <App serviceData={serviceData} />
          </React.StrictMode>
        );


      }
    }
  }
}

renderApp();