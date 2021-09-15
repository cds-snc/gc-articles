import './expander/expander.js';
import './alert/alert.js';
import { confirmSend } from './util';
import { renderNotifyPanel, findListById, getData } from "./dashboard/dashboard.js"

// Provide top-level namespaces for our javascript.
(function () {
  window.CDS = {};
  CDS.confirmSend = confirmSend;
  CDS.Notify = { renderPanel: renderNotifyPanel, findListById, getData };
})();