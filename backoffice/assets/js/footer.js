/**
 * Maneja las peticiones Ajax
 */

var messagesClient = {
  init: function () {
    messagesClient.list();
    setInterval(() => {
      messagesClient.list();
    }, 30000);
  },

  /**
   * Obtiene mediante consulta un arreglo con todos los mensajes en BD
   */
  list: function () {
    $.ajax({
      method: "GET",
      url: application.service_url + "footer.php",
      data: { action: "list" },
    }).done(function (msg) {
      application.log(msg);

      let data = application.parseJson(msg);

      if (data.success == 1) {
        footUIManager.drawList(data);
      }
    });
  },

  /**
   * Obtiene mediante consulta un mensajes desde BD
   */
  get: function (item_id, callback) {
    $.ajax({
      method: "GET",
      url: application.service_url + "footer.php",
      data: {
        action: "get",
        slug: item_id,
      },
    }).done(function (msg) {
      application.log(msg);

      let data = application.parseJson(msg);

      if (data.success == 1) {
        callback(data.news_item);
      }
    });
  },

  /**
   * Actualiza el estado del mensaje en BD
   */
  markAsRead: function (item_id, callback) {
    $.ajax({
      method: "POST",
      url: application.service_url + "footer.php",
      data: {
        action: "markasviewed",
        mid: item_id,
      },
    }).done(function (msg) {
      application.log(msg);

      let data = application.parseJson(msg);

      if (data.success == 1) {
        callback();
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
  drawList: function (dataset) {
    if (!dataset) {
      return false;
    }

    let messages = dataset.messages;

    let wrapper = document.getElementById("foot-wrapper");
    wrapper.innerHTML = "";

    messages.forEach(function (msg) {
      wrapper.innerHTML = footUIManager.drawItem(msg);
    });
  },

  /**
   * construye elemento servicio listo para integrar al DOM
   */
  drawItem: function (itemData) {
    if (!itemData) {
      return false;
    }

    let id = itemData.id;
    let address = itemData.direccion;
    let phone = itemData.telefono;
    let mail = itemData.correo;
    localStorage.setItem("id_footer", id);
    let itemHtml = /*html*/ `        
            <tr>
            <td>
              <span class="tools-name">Dirección</span>
            </td>
            <td>
            <span>${address}</span>
            </td>
          </tr>
          <tr>
          <td>
            <span class="tools-name">Telefono</span>
          </td>
          <td>
          <span>${phone}</span>
          </td>
        </tr>
        <tr>
        <td>
          <span class="tools-name">Correo</span>
        </td>
        <td>
        <span>${mail}</span>
        </td>
      </tr>
        `;

    return itemHtml;
  },

  /**
   * Muestra un modal con el detalle del mensaje y lo marca como leido en la bd
   */
  editItem: function () {
    if (!localStorage.getItem("id_footer")) {
      return false;
    }
    let id_foot = localStorage.getItem("id_footer");

    messagesClient.get(id_foot, function (data) {
      var inputID = $("#idFoot");
      inputID.val(data.id);
      quill.clipboard.dangerouslyPasteHTML(0, data.direccion);
      var inputPhone = $("#editPhone");
      inputPhone.val(data.telefono);
      var inputMail = $("#editMail");
      inputMail.val(data.correo);
      document.getElementById("foot-detail").classList.add("d-block");
    });
  },

  /**
   * Guarda los cambios del modo edicion en la bd y devueve el elemento al modo solo lectura
   */
  hideItemUpdateModal: function (id) {
    document.getElementById("foot-detail").classList.remove("d-block");
  },
  updateNews: function () {
    if (
      document.querySelector("#editAddress .ql-editor").getInnerHTML()
        .length === 0 ||
      document.getElementById("editPhone").value.trim().length === 0 ||
      document.getElementById("editMail").value.trim().length === 0
    ) {
      alert("Debes completar los campos para continuar");
      return false;
    }
    let dataset = {
      id: document.getElementById("idFoot").value,
      address: document.querySelector("#editAddress .ql-editor").getInnerHTML(),
      phone: document.getElementById("editPhone").value,
      mail: document.getElementById("editMail").value,
    };

    $.ajax({
      method: "POST",
      url: application.service_url + "footer.php",
      data: {
        action: "update",
        id: dataset.id,
        direccion: dataset.address,
        telefono: dataset.phone,
        correo: dataset.mail,
      },
    }).done(function (msg) {
      alert("Datos Actualizados");
      window.location.reload();
    });
  },
};
