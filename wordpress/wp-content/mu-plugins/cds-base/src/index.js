import './expander/expander.js';
import './alert/alert.js';
import { confirmSend } from './util';
import { renderNotifyPanel } from "./dashboard/dashboard.js"

// Provide top-level namespaces for our javascript.
(function () {
  window.CDS = {};
  CDS.confirmSend = confirmSend;
  CDS.renderNotifyPanel = renderNotifyPanel;
})();