import './expander/expander.js';
import './alert/alert.js';
import { confirmSend } from './util';

// Provide top-level namespaces for our javascript.
(function() {
  window.CDS = {};
  CDS.confirmSend =  confirmSend;
})();



