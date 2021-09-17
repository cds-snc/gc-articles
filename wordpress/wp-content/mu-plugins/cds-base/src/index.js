import 'Blocks/expander';
import 'Blocks/alert';
import { confirmSend } from 'Notify/util.js';
import { NotifyPanel, findListById, getData } from "Notify/NotifyPanel"
import { LoginsPanel } from "TrackLogins/LoginsPanel";

const { render } = wp.element;

export const renderNotifyPanel = ({ sendTemplateLink }) => {
  render(<NotifyPanel sendTemplateLink={sendTemplateLink} />, document.getElementById("notify-panel"));
}

export const renderLoginsPanel = () => {
  render(<LoginsPanel />, document.getElementById("logins-panel"))
}

const getListData = async () => {
  CDS.Notify.listCounts = await CDS.Notify.getData("wp-notify/v1/list_counts");
}

(function ($) {
  window.CDS = {};
  CDS.confirmSend = confirmSend;
  CDS.Notify = { renderPanel: renderNotifyPanel, findListById, getData };
  CDS.renderLoginsPanel = renderLoginsPanel;

  $('#name + table').remove();

  getListData();

  const handleSubmit = async function (e) {
    e.preventDefault();
    let text = '';
    try {
      let val = $("#list_id").val();
      val = val.split("~");
      const list = CDS.Notify.findListById(CDS.Notify.listCounts, val[0]);
      text = "This list has " + list.subscriber_count + " subscribers.  ";
      text += "You won't be able to revert this";
    } catch (e) {
      //
    }

    let confirmed = await CDS.confirmSend(text);

    if (confirmed) {
      $("body").off("submit", "#email_sender", handleSubmit);
      $('#cds-send-notify-template').trigger("click");
      $('#cds-send-notify-template').hide();
    }
  };

  $("body").on("submit", "#email_sender", handleSubmit);
})(jQuery);