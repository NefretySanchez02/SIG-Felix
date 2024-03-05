var application = {
  service_url: "backoffice/services/",
  debugMode: false,

  /**
   * log to console
   */
  log: function (msg, lineTxt = "") {
    if (this.debugMode) console.log(msg, lineTxt);
  },

  /**
   * filtra y parsea la respuesta desde JSON
   */
  parseJson: function (rawData) {
    var sub_msg = rawData.substring(
      rawData.indexOf("{"),
      rawData.lastIndexOf("}") + 1
    );
    return JSON.parse(sub_msg);
  },

  /**
   * formatea una fecha y hora a formato latino
   */
  formatDate: function (datetime, includeTime = false) {
    const [date, time] = datetime.split(" ");
    const [ano, month, day] = date.split("-");
    const timeOutput = includeTime ? " " + time : "";
    return day + "/" + month + "/" + ano + " " + timeOutput;
  },
};
