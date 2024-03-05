/**
 * Maneja las peticiones Ajax
 */
var newsClient = {
  init: function () {
    newsClient.list();
  },

  initForIndex: function () {
    newsClient.list(true);
  },

  /**
   * Obtiene mediante consulta un arreglo con todos los mensajes en BD
   */
  list: function (indexPageFlag = false) {
    $.ajax({
      method: "GET",
      url: application.service_url + "tools.php",
      data: { action: "list" },
    }).done(function (msg) {
      let data = application.parseJson(msg);

      if (data.success == 1) {
        toolsUIManager.drawList(data, indexPageFlag);
      }
    });
  },

};

/**
 * Maneja el comportamiento de los elementos en pantalla
 */
var toolsUIManager = {
  /**
   * construye la lista de servicios y la inyecta en el DOM
   */
  drawList: function (dataset, indexPageFlag = false) {
    if (!dataset) {
      return false;
    }

    let messages = dataset.messages;

    let wrapper = document.getElementById("tools-content");
    wrapper.innerHTML = "";
    messages.forEach(function (msg, index) {
      wrapper.appendChild(toolsUIManager.drawItem(msg, indexPageFlag));
    });
   
  },

  /**
   * construye elemento noticia listo para integrar al DOM
   */
  drawItem: function (itemData, indexPageFlag = false) {
    if (!itemData) {
      return false;
    }

    let titulo = itemData.titulo;
    let subtitulo = itemData.subtitulo;
    let imagen = itemData.imagen;
    let txtBtn = itemData.texto_boton;
    let urlBtn = itemData.url_boton;

    let itemHtml = /*html*/ `
                <div class="interactive-banners-box bg-dark-slate-blue border-radius-6px">
                    <div class="interactive-banners-box-image">
                        <img src="backoffice/assets/img/herramientas/${imagen}" alt="" />
                        <div class="overlay-bg bg-gradient-dark-slate-blue-transparent"></div>
                    </div>
                    <div class="fancy-text-content padding-65px-lr md-padding-55px-lr xs-padding-30px-lr">
                        <div class="margin-10px-bottom">${subtitulo}
                        </div>
                        <div
                            class="alt-font text-extra-large text-white margin-15px-bottom w-40 lg-w-90 sm-w-50 xs-w-90 md-margin-5px-bottom">
                            ${titulo}</div>
                        <a href="${urlBtn}" class="btn btn-fancy btn-very-small btn-round-edge margin-15px-top">${txtBtn}</a>
                    </div>
                </div>
          `;
    let div = document.createElement("div");
    div.setAttribute("class", "swiper-slide interactive-banners-style-07");
    div.innerHTML = itemHtml;
    return div;
  },
};
