import 'Blocks/expander';
import 'Blocks/alert';
import { NotifyPanel } from "Notify/NotifyPanel"
import { LoginsPanel } from "TrackLogins/LoginsPanel";

const { render } = wp.element;

export const renderNotifyPanel = ({ sendTemplateLink }) => {
  render(<NotifyPanel sendTemplateLink={sendTemplateLink} />, document.getElementById("notify-panel"));
}

export const renderLoginsPanel = () => {
  render(<LoginsPanel />, document.getElementById("logins-panel"))
}

(function ($) {
  window.CDS = window.CDS || {};
  CDS.Notify = { renderPanel: renderNotifyPanel };
  CDS.renderLoginsPanel = renderLoginsPanel;
  $('#name + table').remove();
})(jQuery);