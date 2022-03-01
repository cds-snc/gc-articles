import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import App from './App';
import { List } from '../../../Notify/src/Types';

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

if (document.getElementById("list-manager-app")) {
  // render outside WP
  ReactDOM.render(
    <React.StrictMode>
      <App />
    </React.StrictMode>,
    document.getElementById("list-manager-app")
  );

}