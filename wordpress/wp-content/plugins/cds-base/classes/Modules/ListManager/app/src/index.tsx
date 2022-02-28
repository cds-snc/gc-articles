import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import App from './App';

declare global {
  interface Window {
    renderListManager: (el: string) => void
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