/**
 * Maneja las peticiones Ajax
 */
var contactClient = {
  init: function () {
    contactClient.list();
  },

  initForIndex: function () {
    contactClient.list(true);
  },

  /**
   * Obtiene mediante consulta un arreglo con todos los mensajes en BD
   */
  list: function (indexPageFlag = false) {
    $.ajax({
      method: "GET",
      url: application.service_url + "contact.php",
      data: { action: "list" },
    }).done(function (msg) {
      let data = application.parseJson(msg);

      if (data.success == 1) {
        contactUIManager.drawList(data, indexPageFlag);
      }
    });
  },
};

/**
 * Maneja el comportamiento de los elementos en pantalla
 */
var contactUIManager = {
  /**
   * construye la lista de servicios y la inyecta en el DOM
   */
  drawList: function (dataset, indexPageFlag = false) {
    if (!dataset) {
      return false;
    }

    let messages = dataset.messages;

    messages.forEach(function (msg, index) {
      contactUIManager.drawItem(msg, indexPageFlag);
    });
  },

  /**
   * construye elemento noticia listo para integrar al DOM
   */
  drawItem: function (itemData, indexPageFlag = false) {
    if (!itemData) {
      return false;
    }
    let mail = itemData.correo;
    let phone = itemData.telefono;
    let whatsapp = itemData.whatsapp;
    let ubicacion = itemData.ubicacion;

    document
      .getElementById("contact-mail")
      .setAttribute("href", "mailto:" + mail + "");
    document
      .getElementById("contact-phone")
      .setAttribute("href", "tel:" + phone + "");
    document.getElementById("contact-whtasapp").setAttribute("href", whatsapp);
    document.getElementById("contact-ubicacion").setAttribute("src", ubicacion);
  },
};
