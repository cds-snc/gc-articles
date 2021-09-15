
const getListData = async () => {
  CDS.Notify.listCounts = await CDS.Notify.getData("wp-notify/v1/list_counts");
}

(function ($) {
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
      $("#email_sender").off('submit', handleSubmit);
      $('#cds-send-notify-template').trigger("click");
      $('#cds-send-notify-template').hide();
    }
  };

  $('#email_sender').on('submit', handleSubmit);
})(jQuery);