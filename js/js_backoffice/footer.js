/**
 * Maneja las peticiones Ajax
 */
var footerClient = {
  init: function () {
    footerClient.list();
  },

  initForIndex: function () {
    footerClient.list(true);
  },

  /**
   * Obtiene mediante consulta un arreglo con todos los mensajes en BD
   */
  list: function (indexPageFlag = false) {
    $.ajax({
      method: "GET",
      url: application.service_url + "footer.php",
      data: { action: "list" },
    }).done(function (msg) {
      let data = application.parseJson(msg);

      if (data.success == 1) {
        footUIManager.drawList(data, indexPageFlag);
      }
    });
  },
};

/**
 * Maneja el comportamiento de los elementos en pantalla
 */
var footUIManager = {
  /**
   * construye la lista de servicios y la inyecta en el DOM
   */
  drawList: function (dataset, indexPageFlag = false) {
    if (!dataset) {
      return false;
    }

    let messages = dataset.messages;
    let wrapper = document.getElementById("footer-content");
    wrapper.innerHTML = "";
    messages.forEach(function (msg, index) {
      wrapper.innerHTML = footUIManager.drawItem(msg, indexPageFlag);
    });
  },

  /**
   * construye elemento noticia listo para integrar al DOM
   */
  drawItem: function (itemData, indexPageFlag = false) {
    if (!itemData) {
      return false;
    }
    
    let direccion = itemData.direccion;
    let phone = itemData.telefono;
    let mail = itemData.correo;

    let itemHtml = /*html*/ `
                            <li>
                                ${direccion}
                            </li>
                            <li><a href="tel:${phone}"><img src="images/img-sig/icono-phone.svg"> ${phone}</a></li>
                            <li><a href="mailto:${mail}"><img src="images/img-sig/icono-mail.svg"> ${mail}</a></li>
            `;
    return itemHtml;
  },
};
