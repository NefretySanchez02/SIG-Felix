/**
 * Maneja las peticiones Ajax
 */
var usersClient = {
  init: function () {
    usersClient.list();
    setInterval(() => {
      usersClient.list();
    }, 30000);
  },

  /**
   * Obtiene mediante consulta un arreglo con todos los usuarios en BD
   */
  list: function () {
    $.ajax({
      method: "GET",
      url: application.service_url + "users.php",
      data: { action: "list" },
    }).done(function (msg) {
      application.log(msg);

      let data = application.parseJson(msg);

      if (data.success == 1) {
        messagesUIManager.drawList(data);
      }
    });
  },

  /**
   * Obtiene mediante consulta un usuarios desde BD
   */
  get: function (item_id, callback) {
    $.ajax({
      method: "GET",
      url: application.service_url + "users.php",
      data: {
        action: "get",
        mid: item_id,
      },
    }).done(function (msg) {
      application.log(msg);

      let data = application.parseJson(msg);

      if (data.success == 1) {
        callback(data.messaje);
      }
    });
  },

  /**
   * Desactiva un usuarios en BD
   */
  setState: function (item_id, callback) {
    $.ajax({
      method: "POST",
      url: application.service_url + "users.php",
      data: {
        action: "set-state",
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
var messagesUIManager = {
  /**
   * construye la lista de servicios y la inyecta en el DOM
   */
  drawList: function (dataset) {
    if (!dataset) {
      return false;
    }

    let messages = dataset.messages;

    let wrapper = document.getElementById("messages-wrapper");
    wrapper.innerHTML = "";

    messages.forEach(function (msg) {
      wrapper.appendChild(messagesUIManager.drawItem(msg));
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
    let name = itemData.nombre;
    let email = itemData.email;
    let level = itemData.level == "ADMIN" ? "Administrador" : "Moderador";
    let estado = itemData.estado == "ACTIVO" ? "enable" : "unable";
    let ultimo_acceso = itemData.ultimo_acceso;

    let itemHtml = /*html*/ `        
        <td>
          <span class="service-name">${name}</span>
        </td>
        <td>
          <span class="d-block">${email}</span>
        </td>
        <td>
          <span class="service-order">${level}</span>
        </td>
        <td>
          <span class="service-order">${ultimo_acceso}</span>
        </td>

        
    `;

    let tr = document.createElement("tr");
    tr.setAttribute("id", "serv-item-" + id);
    tr.setAttribute("class", estado);
    tr.innerHTML = itemHtml;

    return tr;
  },

  /**
   * Muestra un modal con el detalle del mensaje y lo marca como leido en la bd
   */
  showItem: function (id) {
    if (!id) {
      return false;
    }

    usersClient.get(id, function (data) {
      let level = data.level == "ADMIN" ? "Administrador" : "Moderador";
      let estado = data.estado == "ACTIVO" ? "Activo" : "Inactivo";
      document.getElementById("item-user-name").innerText = data.nombre;
      document.getElementById("item-user-email").innerText = data.email;
      document.getElementById("item-user-username").innerText = data.usernm;
      document.getElementById("item-user-level").innerText = level;
      document.getElementById("item-user-access").innerText =
        data.ultimo_acceso;
      document.getElementById("item-user-status").innerText = estado;

      document.getElementById("modal-subject").innerText = data.nombre;

      document.getElementById("user-detail").classList.add("d-block");
    });

    usersClient.markAsRead(id, usersClient.list);
  },

  /**
   * Guarda los cambios del modo edicion en la bd y devueve el elemento al modo solo lectura
   */
  hideItemDetailModal: function (id) {
    document.getElementById("user-detail").classList.remove("d-block");
  },
};
