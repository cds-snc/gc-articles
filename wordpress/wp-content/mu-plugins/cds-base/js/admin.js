(function($) {
  $('#name + table').remove();

  const handleSubmit = async function(e) {
    e.preventDefault();
    let confirmed = await CDS.confirmSend();
    if (confirmed) {
      $("#email_sender").off('submit', handleSubmit);
      $('#cds-send-notify-template').trigger("click");
      $('#cds-send-notify-template').hide();
    }
  };

  $('#email_sender').on('submit', handleSubmit);
})(jQuery);