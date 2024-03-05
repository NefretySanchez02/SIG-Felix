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
        url: application.service_url + "tools.php",
        data: { action: "list" },
      }).done(function (msg) {
        application.log(msg);
  
        let data = application.parseJson(msg);
  
        if (data.success == 1) {
            toolsUIManager.drawList(data);
        }
      });
    },
  
    /**
     * Obtiene mediante consulta un mensajes desde BD
     */
    get: function (item_id, callback) {
      $.ajax({
        method: "GET",
        url: application.service_url + "tools.php",
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
        url: application.service_url + "tools.php",
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
  
    /**
     * Eliminar una noticia en la BD
     */
  
    deleteNews: function (item_id) {
      $.ajax({
        method: "POST",
        url: application.service_url + "tools.php",
        data: {
          action: "delete",
          id_item: item_id,
        },
      }).done(function (msg) {
        console.log(msg);
      });
    },
  
    /**
     * Editar una noticia en la BD
     */
  
    updateImgTools: function (foto) {
      var formData = new FormData();
      formData.append("image", foto);
      formData.append("action", "updatePhoto");
      if (typeof formData.get("image") == "object") {
        $.ajax({
          url: application.service_url + "tools.php",
          type: "POST",
          data: formData,
          mimeType: "multipart/form-data",
          dataType: "html",
          contentType: false,
          processData: false,
          success: function (msg, textStatus, jqXHR) {
            console.log(msg);
          },
          error: function (jqXHR, textStatus, errorThrown) {},
        });
      }
  
      /*  */
    }
  };
  
  /**
   * Maneja el comportamiento de los elementos en pantalla
   */
  var toolsUIManager = {
    /**
     * construye la lista de servicios y la inyecta en el DOM
     */
    drawList: function (dataset) {
      if (!dataset) {
        return false;
      }
  
      let messages = dataset.messages;
  
      let wrapper = document.getElementById("tools-wrapper");
      wrapper.innerHTML = "";
  
      messages.forEach(function (msg) {
        wrapper.appendChild(toolsUIManager.drawItem(msg));
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
      let name = itemData.titulo;
  
      let itemHtml = /*html*/ `        
            <td>
              <span class="tools-name">${name}</span>
            </td>
            <td>
              <a class="text-info hover-effect" onclick="toolsUIManager.viewModalItem('${id}')">
                <i class="material-icons">visibility</i>
              </a>
              <a class="text-info hover-effect" onclick="toolsUIManager.editItem('${id}')">
              <i class="material-icons">mode_edit</i>
            </a>
            <a class="text-info hover-effect" onclick="toolsUIManager.viewModalDelete('${id}','${name}')">
              <i class="material-icons">do_not_disturb_alt</i>
            </a>
            
          </td>
           
        `;
  
      let tr = document.createElement("tr");
      tr.setAttribute("id", "serv-item-" + id);
      tr.setAttribute("class", status);
      tr.innerHTML = itemHtml;
  
      return tr;
    },
  
    /**
     * Muestra un modal con el detalle del mensaje y lo marca como leido en la bd
     */
    editItem: function (id) {
      if (!id) {
        return false;
      }
  
      messagesClient.get(id, function (data) {
        var inputID = $("#idTools");
        inputID.val(data.id);
        var inputTitular = $("#editTitle");
        inputTitular.val(data.titulo);
        var inputSubtitle = $("#editSubtitle");
        inputSubtitle.val(data.subtitulo);
        var inputUrlBtn = $("#editTxtBtn");
        inputUrlBtn.val(data.texto_boton);
        var inputTxtBtn = $("#editUrlBtn");
        inputTxtBtn.val(data.url_boton);
        var inputImg = $("#editImg-name");
        inputImg.val(data.imagen);
        document.getElementById("tools-detail").classList.add("d-block");
      });
    },
  
    viewModalDelete: function (id, name) {
      if (!id) {
        return false;
      }
  
      document.getElementById("item-tools-name").innerText = name;
      document.getElementById("delete-tools").classList.add("d-block");
      localStorage.setItem("id_tools", id);
    },
  
    removeItem: function () {
      if (!localStorage.getItem("id_tools")) {
        return false;
      }
      let id_services = localStorage.getItem("id_tools");
      messagesClient.deleteNews(id_services);
      document.getElementById("delete-tools").classList.remove("d-block");
      window.location.reload();
    },
  
    viewModalItem: function (id) {
      if (!id) {
        return false;
      }
  
      messagesClient.get(id, function (data) {
        var inputTitular = $("#item-title");
        inputTitular[0].innerText = data.titulo;
        var inputSubtitle = $("#item-subtitle");
        inputSubtitle[0].innerText = data.subtitulo;
        var inputImg = $("#item-img");
        inputImg[0].innerHTML = ` <img src='assets/img/herramientas/${data.imagen}' style="width: 50%;" />`;
        var inputUrlBtn = $("#item-urlBtn");
        inputUrlBtn[0].innerText = data.url_boton;
        var inputTxtBtn = $("#item-txtBtn");
        inputTxtBtn[0].innerText = data.texto_boton;
       
        document.getElementById("view-tools").classList.add("d-block");
      });
    },
  
    /**
     * Guarda los cambios del modo edicion en la bd y devueve el elemento al modo solo lectura
     */
    hideItemDetailModal: function (id) {
      document.getElementById("delete-tools").classList.remove("d-block");
    },
    hideItemUpdateModal: function (id) {
      document.getElementById("tools-detail").classList.remove("d-block");
    },
    hideItemCreateModal: function (id) {
      document.getElementById("create-tools").classList.remove("d-block");
    },
    hideItemViewModal: function (id) {
      document.getElementById("view-tools").classList.remove("d-block");
    },
    updateNews: function () {
      if (
        document.getElementById("editTitle").value.trim().length === 0 ||
        document.getElementById("editSubtitle").value.trim().length === 0 ||
        document.getElementById("editTxtBtn").value.trim().length === 0 ||  
        document.getElementById("editUrlBtn").value.trim().length === 0 ||  
        document.getElementById("editImg-name").value.trim().length === 0 
      ) {
        alert("Debes completar los campos para continuar");
        return false;
      }
      let dataset = {
        id: document.getElementById("idTools").value,
        title: document.getElementById("editTitle").value,
        subtitle: document.getElementById("editSubtitle").value,
        imagen: document.getElementById("editImg-file").files[0],
        txtBtn: document.getElementById("editTxtBtn").value,
        urlBtn: document.getElementById("editUrlBtn").value
      };
  
      if (dataset.imagen == undefined) {
        dataset.imagen = document.getElementById("editImg-name").value;
      } else {
        dataset.imagen = document.getElementById("editImg-file").files[0];
      }
  
      var dataImg;
      if (typeof dataset.imagen == "object") {
        dataImg = dataset.imagen.name;
        messagesClient.updateImgTools(dataset.imagen);
      } else {
        dataImg = dataset.imagen;
      }
      
     $.ajax({
        method: "POST",
        url: application.service_url + "tools.php",
        cache: false,
        crossDomain: true,
        data: {
          action: "update",
          id: dataset.id,
          titulo: dataset.title,
          subtitulo: dataset.subtitle,
          imagen: dataImg,
          texto_boton: dataset.txtBtn,
          url_boton: dataset.urlBtn
        },
      }).done(function (msg) {
        alert("Herramienta Actualizado");
        window.location.reload();
      }); 
    },
    addToolsModal: function () {
      document.getElementById("create-tools").classList.add("d-block");
    },
    addTools: function () {
      if (
        document.getElementById("createTitle").value.trim().length === 0 ||
        document.getElementById("createSubtitle").value.trim().length === 0 ||
        document.getElementById("createImg-name").value.trim().length === 0 ||  
        document.getElementById("createTxtBtn").value.trim().length === 0 ||  
        document.getElementById("createUrlBtn").value.trim().length === 0 
      ) {
        alert("Debes completar los campos para continuar");
        return false;
      }
  
      let dataset = {
        title: document.getElementById("createTitle").value,
        subtitle: document.getElementById("createSubtitle").value,
        imagen:  document.getElementById("createImg-file").files[0],
        txtBtn: document.getElementById("createTxtBtn").value,
        urlBtn: document.getElementById("createUrlBtn").value
      };

     $.ajax({
        method: "POST",
        url: application.service_url + "tools.php",
        data: {
          action: "create",
          titulo: dataset.title,
          subtitulo: dataset.subtitle,
          imagen: dataset.imagen.name,
          texto_boton: dataset.txtBtn,
          url_boton: dataset.urlBtn
        },
      }).done(function (msg) {
        messagesClient.updateImgTools(dataset.imagen);
        alert("Herramienta creado");
        window.location.reload();
      }); 
    },

  };
  
  $(".form-file-simple .inputFileVisible").click(function () {
    $(this).siblings(".inputFileHidden").trigger("click");
  });
  
  $(".form-file-simple .inputFileHidden").change(function () {
    var filename = $(this)
      .val()
      .replace(/C:\\fakepath\\/i, "");
    $(this).siblings(".inputFileVisible").val(filename);
  });
  