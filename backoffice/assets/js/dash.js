let dash = {
  init: () => {
    dash.getStats();
    setInterval(() => {
      dash.getStats();
    }, 30000);
  },

  getStats: () => {
    $.ajax({
      method: "GET",
      url: application.service_url + "messages.php",
      data: { action: "stats" },
    }).done(function (rawData) {
      let data = application.parseJson(rawData);

      if (data.success == 1) {
        let msg_count = data.messages_count;
        document.getElementById("mod-unread-messages").innerText = msg_count;
      }
    });
  },
};
