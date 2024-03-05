var application = {
  service_url: "services/",
  debugMode: false,

  /**
   * log to console
   */
  log: function(msg, lineTxt = ""){
    if(this.debugMode)
      console.log(msg, lineTxt);
  },

  /**
   * filtra y parsea la respuesta desde JSON
   */
   parseJson: function(rawData){
    var sub_msg = rawData.substring(rawData.indexOf("{"), rawData.lastIndexOf("}") + 1);
    return JSON.parse(sub_msg);
  }
}