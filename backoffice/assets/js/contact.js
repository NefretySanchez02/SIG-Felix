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
        url: application.service_url + "contact.php",
        data: { action: "list" },
      }).done(function (msg) {
        application.log(msg);
  
        let data = application.parseJson(msg);
  
        if (data.success == 1) {
          contactUIManager.drawList(data);
        }
      });
    },
  
    /**
     * Obtiene mediante consulta un mensajes desde BD
     */
    get: function (item_id, callback) {
      $.ajax({
        method: "GET",
        url: application.service_url + "contact.php",
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
        url: application.service_url + "contact.php",
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
  var contactUIManager = {
    /**
     * construye la lista de servicios y la inyecta en el DOM
     */
    drawList: function (dataset) {
      if (!dataset) {
        return false;
      }
  
      let messages = dataset.messages;
  
      let wrapper = document.getElementById("contact-wrapper");
  
      messages.forEach(function (msg) {
        wrapper.innerHTML = contactUIManager.drawItem(msg);
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
      let mail = itemData.correo;
      let phone = itemData.telefono;
      let whatsapp = itemData.whatsapp;
      let ubicacion = itemData.ubicacion;
      localStorage.setItem("id_contact", id);
      let itemHtml = /*html*/ `        
              <tr>
              <td>
                <span class="tools-name">Correo</span>
              </td>
              <td>
              <span>${mail}</span>
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
            <span class="tools-name">Whatsapp</span>
          </td>
          <td>
          <span style="word-break: break-all;">${whatsapp}</span>
          </td>
        </tr>
        <tr>
          <td>
            <span class="tools-name" >Ubicacion</span>
          </td>
          <td>
          <span style="word-break: break-all;">${ubicacion}</span>
          </td>
        </tr>
          `;
  
      return itemHtml;
    },
  
    /**
     * Muestra un modal con el detalle del mensaje y lo marca como leido en la bd
     */
    editItem: function () {
      if (!localStorage.getItem("id_contact")) {
        return false;
      }
      let id_foot = localStorage.getItem("id_contact");
  
      messagesClient.get(id_foot, function (data) {
        var inputID = $("#idContact");
        inputID.val(data.id);
        var inputMail = $("#editMail");
        inputMail.val(data.correo);
        var inputPhone = $("#editPhone");
        inputPhone.val(data.telefono);
        var inputWhat = $("#editWhat");
        inputWhat.val(data.whatsapp);
        var inputUbi= $("#editUbi");
        inputUbi.val(data.ubicacion);
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
        document.getElementById("editMail").value.trim().length === 0 ||
        document.getElementById("editPhone").value.trim().length === 0 ||
        document.getElementById("editWhat").value.trim().length === 0 ||
        document.getElementById("editUbi").value.trim().length === 0 
      ) {
        alert("Debes completar los campos para continuar");
        return false;
      }
      let dataset = {
        id: document.getElementById("idContact").value,
        mail: document.getElementById("editMail").value,
        phone: document.getElementById("editPhone").value,
        whatsapp: document.getElementById("editWhat").value,
        ubicacion: document.getElementById("editUbi").value,
      };
  
      $.ajax({
        method: "POST",
        url: application.service_url + "contact.php",
        data: {
          action: "update",
          id: dataset.id,
          correo: dataset.mail,
          telefono: dataset.phone,
          whatsapp: dataset.whatsapp,
          ubicacion: dataset.ubicacion
        },
      }).done(function (msg) {
        alert("Datos Actualizados");
        window.location.reload();
      });
    },
  };