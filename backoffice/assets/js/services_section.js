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
      url: application.service_url + "services-section.php",
      data: { action: "list" },
    }).done(function (msg) {
      application.log(msg);

      let data = application.parseJson(msg);

      if (data.success == 1) {
        servicesUIManager.drawList(data);
      }
    });
  },

  /**
   * Obtiene mediante consulta un mensajes desde BD
   */
  get: function (item_id, callback) {
    $.ajax({
      method: "GET",
      url: application.service_url + "services-section.php",
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
      url: application.service_url + "services-section.php",
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
      url: application.service_url + "services-section.php",
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

  updateImgService: function (foto) {
    var formData = new FormData();
    formData.append("image", foto);
    formData.append("action", "updatePhoto");
    if (typeof formData.get("image") == "object") {
      $.ajax({
        url: application.service_url + "services-section.php",
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
var servicesUIManager = {
  /**
   * construye la lista de servicios y la inyecta en el DOM
   */
  drawList: function (dataset) {
    if (!dataset) {
      return false;
    }

    let messages = dataset.messages;

    let wrapper = document.getElementById("services-wrapper");
    wrapper.innerHTML = "";

    messages.forEach(function (msg) {
      wrapper.appendChild(servicesUIManager.drawItem(msg));
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
            <span class="service-name">${name}</span>
          </td>
          <td>
            <a class="text-info hover-effect" onclick="servicesUIManager.viewModalItem('${id}')">
              <i class="material-icons">visibility</i>
            </a>
            <a class="text-info hover-effect" onclick="servicesUIManager.editItem('${id}')">
            <i class="material-icons">mode_edit</i>
          </a>
          <a class="text-info hover-effect" onclick="servicesUIManager.viewModalDelete('${id}','${name}')">
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
      var inputID = $("#idService");
      inputID.val(data.id);
      var inputTitular = $("#editTitle");
      inputTitular.val(data.titulo);
      var inputSubtitle = $("#editSubtitle");
      inputSubtitle.val(data.subtitulo_banner);
      var inputTitle = $("#editTitleB");
      inputTitle.val(data.titulo_banner);
      quill.clipboard.dangerouslyPasteHTML(0, data.parrafo_banner);
      var inputUrlBtn = $("#editTxtBtn");
      inputUrlBtn.val(data.texto_boton);
      var inputTxtBtn = $("#editUrlBtn");
      inputTxtBtn.val(data.url_boton);
      var inputImg = $("#editImg-name");
      inputImg.val(data.imagen_1);
      var inputImgT = $("#editImgT-name");
      inputImgT.val(data.imagen_2);
      document.getElementById("services-detail").classList.add("d-block");
    });
  },

  viewModalDelete: function (id, name) {
    if (!id) {
      return false;
    }

    document.getElementById("item-services-name").innerText = name;
    document.getElementById("delete-services").classList.add("d-block");
    localStorage.setItem("id_services", id);
  },

  removeItem: function () {
    if (!localStorage.getItem("id_services")) {
      return false;
    }
    let id_services = localStorage.getItem("id_services");
    messagesClient.deleteNews(id_services);
    document.getElementById("delete-services").classList.remove("d-block");
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
      inputSubtitle[0].innerText = data.subtitulo_banner;
      var inputTitle = $("#item-titleB");
      inputTitle[0].innerText = data.titulo_banner;
      var inputTxt = $("#item-txt");
      inputTxt[0].innerText = data.parrafo_banner;
      var inputUrlBtn = $("#item-urlBtn");
      inputUrlBtn[0].innerText = data.url_boton;
      var inputTxtBtn = $("#item-txtBtn");
      inputTxtBtn[0].innerText = data.texto_boton;
      var inputImg = $("#item-img");
      inputImg[0].innerHTML = ` <img src='assets/img/services/${data.imagen_1}' style="width: 30%;" />`;
      var inputImgT = $("#item-imgT");
      inputImgT[0].innerHTML =  ` <img src='assets/img/services/${data.imagen_2}' style="width: 30%;" />`;
     
      document.getElementById("view-services").classList.add("d-block");
    });
  },

  /**
   * Guarda los cambios del modo edicion en la bd y devueve el elemento al modo solo lectura
   */
  hideItemDetailModal: function (id) {
    document.getElementById("delete-services").classList.remove("d-block");
  },
  hideItemUpdateModal: function (id) {
    document.getElementById("services-detail").classList.remove("d-block");
  },
  hideItemCreateModal: function (id) {
    document.getElementById("create-services").classList.remove("d-block");
  },
  hideItemViewModal: function (id) {
    document.getElementById("view-services").classList.remove("d-block");
  },
  updateNews: function () {
    if (
      document.getElementById("editTitle").value.trim().length === 0 ||
      document.getElementById("editSubtitle").value.trim().length === 0 ||
      document.getElementById("editTitleB").value.trim().length === 0 ||  
      document.querySelector("#editText .ql-editor").getInnerHTML().length === 0 ||  
      document.getElementById("editTxtBtn").value.trim().length === 0 ||  
      document.getElementById("editUrlBtn").value.trim().length === 0 ||  
      document.getElementById("editImg-name").value.trim().length === 0 ||  
      document.getElementById("editImgT-name").value.trim().length === 0
    ) {
      alert("Debes completar los campos para continuar");
      return false;
    }
    let dataset = {
      id: document.getElementById("idService").value,
      title: document.getElementById("editTitle").value,
      subtitle: document.getElementById("editSubtitle").value,
      titleB: document.getElementById("editTitleB").value,
      txtB: document.querySelector("#editText .ql-editor").getInnerHTML(),
      txtBtn: document.getElementById("editTxtBtn").value,
      urlBtn: document.getElementById("editUrlBtn").value,
      imgP: document.getElementById("editImg-file").files[0],
      imgT: document.getElementById("editImgT-file").files[0],
    };

    if (dataset.imgP == undefined) {
      dataset.imgP = document.getElementById("editImg-name").value;
    } else {
      dataset.imgP = document.getElementById("editImg-file").files[0];
    }

    if (dataset.imgT == undefined) {
      dataset.imgT = document.getElementById("editImgT-name").value;
    } else {
      dataset.imgT = document.getElementById("editImgT-file").files[0];
    }

    var dataImg;
    var dataImgT;
    if (typeof dataset.imgP == "object") {
      dataImg = dataset.imgP.name;
      messagesClient.updateImgService(dataset.imgP);
    } else {
      dataImg = dataset.imgP;
    }

    if (typeof dataset.imgT == "object") {
      dataImgT = dataset.imgT.name;
      messagesClient.updateImgService(dataImgT);
    } else {
      dataImgT = dataset.imgT;
    }
    
    $.ajax({
      method: "POST",
      url: application.service_url + "services-section.php",
      cache: false,
      crossDomain: true,
      data: {
        action: "update",
        id: dataset.id,
        titulo: dataset.title,
        subtitulo_banner: dataset.subtitle,
        titulo_banner: dataset.titleB,
        parrafo_banner: dataset.txtB,
        texto_boton: dataset.txtBtn,
        url_boton: dataset.urlBtn,
        imagen_1: dataImg,
        imagen_2: dataImgT
      },
    }).done(function (msg) {
      alert("Servicio Actualizado");
      window.location.reload();
    }); 
  },
  addServiceModal: function () {
    document.getElementById("create-services").classList.add("d-block");
  },
  addServices: function () {
    if (
      document.getElementById("createTitle").value.trim().length === 0 ||
      document.getElementById("createSubtitle").value.trim().length === 0 ||
      document.getElementById("createTitleB").value.trim().length === 0 ||  
      document.querySelector("#createText .ql-editor").getInnerHTML().length === 0 ||  
      document.getElementById("createTxtBtn").value.trim().length === 0 ||  
      document.getElementById("createUrlBtn").value.trim().length === 0 ||  
      document.getElementById("createImg-file").value.trim().length === 0 ||  
      document.getElementById("createImgT-file").value.trim().length === 0
    ) {
      alert("Debes completar los campos para continuar");
      return false;
    }

    let dataset = {
      title: document.getElementById("createTitle").value,
      subtitle: document.getElementById("createSubtitle").value,
      titleB: document.getElementById("createTitleB").value,
      txtB: document.querySelector("#createText .ql-editor").getInnerHTML(),
      txtBtn: document.getElementById("createTxtBtn").value,
      urlBtn: document.getElementById("createUrlBtn").value,
      imgP: document.getElementById("createImg-file").files[0],
      imgT: document.getElementById("createImgT-file").files[0],
    };
    $.ajax({
      method: "POST",
      url: application.service_url + "services-section.php",
      data: {
        action: "create",
        titulo: dataset.title,
        subtitulo_banner: dataset.subtitle,
        titulo_banner: dataset.titleB,
        parrafo_banner: dataset.txtB,
        texto_boton: dataset.txtBtn,
        url_boton: dataset.urlBtn,
        imagen_1: dataset.imgP.name,
        imagen_2: dataset.imgT.name
      },
    }).done(function (msg) {
      messagesClient.updateImgService(dataset.imgP);
      messagesClient.updateImgService(dataset.imgT);
      alert("Servicio creado");
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
