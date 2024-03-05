/**
 * Maneja las peticiones Ajax
 */
var servicesClient = {
  init: function () {
    servicesClient.list();
  },

  initForIndex: function () {
    servicesClient.list(true);
  },

  /**
   * Obtiene mediante consulta un arreglo con todos los mensajes en BD
   */
  list: function (indexPageFlag = false) {
    $.ajax({
      method: "GET",
      url: application.service_url + "services-section.php",
      data: { action: "list" },
    }).done(function (msg) {
      let data = application.parseJson(msg);

      if (data.success == 1) {
        servicesUIManager.drawList(data, indexPageFlag);
      }
    });
  },
};

/**
 * Maneja el comportamiento de los elementos en pantalla
 */
var servicesUIManager = {
  /**
   * construye la lista de servicios y la inyecta en el DOM
   */
  drawList: function (dataset, indexPageFlag = false) {
    if (!dataset) {
      return false;
    }

    let messages = dataset.messages;

    let wrapper = document.getElementById("services-content");
    wrapper.innerHTML = "";
    let titleWrapper = document.getElementById("services-title");
    titleWrapper.innerHTML = "";
    messages.forEach(function (msg, index) {
      titleWrapper.appendChild(
        servicesUIManager.drawTitle(msg, index, indexPageFlag)
      );
    });
    messages.forEach(function (msg, index) {
      wrapper.appendChild(
        servicesUIManager.drawItem(msg, index, indexPageFlag)
      );
    });
  },

  /**
   * construye elemento noticia listo para integrar al DOM
   */
  drawItem: function (itemData, index, indexPageFlag = false) {
    if (!itemData) {
      return false;
    }

    let id = itemData.id;
    let titulo = itemData.titulo;
    let subtitulo = itemData.subtitulo_banner;
    let tituloB = itemData.titulo_banner;
    let content = itemData.parrafo_banner;
    let urlBtn = itemData.url_boton;
    let txtBtn = itemData.texto_boton;
    let img1 = itemData.imagen_1;
    let img2 = itemData.imagen_2;

    let itemHtml = /*html*/ `
                                <div class="row align-items-center">
                                    <div class="col-12 col-md-6 text-right sm-margin-40px-bottom ">

                                        <div class="s-c-tabs__image position-relative">
                                            <div class="s-c-tabs__image__back">
                                                <img src="backoffice/assets/img/services/${img2}" alt="" />
                                            </div>
                                            <div class="s-c-tabs__image__front">
                                                <img src="backoffice/assets/img/services/${img1}" alt="" />
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-5 offset-lg-1 col-md-6 text-center text-sm-left">
                                        <span
                                            class="alt-font text-medium font-weight-700 margin-15px-bottom d-inline-block">${subtitulo}</span>
                                        <h5 class="alt-font font-weight-700 margin-35px-bottom md-margin-30px-bottom">${tituloB}</h5>
                                        ${content}
                                        <a href="${urlBtn}" class="btn btn-fancy btn-medium">${txtBtn}</a>
                                    </div>
                                </div>
            `;
    let div = document.createElement("div");
    if (index == 0) {
      div.setAttribute("class", "tab-pane fade in active show");
    } else {
      div.setAttribute("class", "tab-pane fade");
    }
    div.setAttribute("id", `tab-${id}`);
    div.innerHTML = itemHtml;
    return div;
  },
  drawTitle: function (itemData, index, indexPageFlag = false) {
    if (!itemData) {
      return false;
    }

    let id = itemData.id;
    let titulo = itemData.titulo;
    let active = "";

    if (index == 0) {
      active = "active";
    }

    let itemHtml = /*html*/ `
                <a class="nav-link ${active}" data-toggle="tab"
                href="#tab-${id}">${titulo}</a><span class="tab-border bg-extra-dark-gray"></span>
            `;
    let div = document.createElement("li");
    div.setAttribute("class", "nav-item");
    div.setAttribute("id", `tab-${id}`);
    div.innerHTML = itemHtml;
    return div;
  },
};
