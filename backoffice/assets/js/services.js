
/**
 * Maneja las peticiones Ajax
 */
var servicesClient = {

  /**
   * filtra y parsea la respuesta a JSON
   */
  parseToJson: function(rawData){
    var sub_msg = rawData.substring(rawData.indexOf("{"), rawData.lastIndexOf("}") + 1);
    return JSON.parse(sub_msg);
  },

  /**
   * Obtiene mediante consulta un arreglo con todos los servicios en BD
   */
  get: function (){
    $.ajax({
      method: "GET",
      url: app.service_url + "services.php",
      data: {action: "get"}
    }).done(function (msg) {
        app.log(msg);

        let data = servicesClient.parseToJson(msg);

        if(data.success == 1){
          servicesUIManager.drawServicesList(data);
        }
    });
  },


  /**
   * Crea un servicio en BD
   */
  create: function (){
    let name = document.getElementById('new-service-name').value;
    let image = document.getElementById('new-service-img').value;
    let order = document.getElementById('new-service-order').value;

    $.ajax({
      method: "POST",
      url: app.service_url + "services.php",
      data: {
        action: "create",
        name: name,
        order: order,
        image: image
      }
    }).done(function (msg) {
        app.log(msg);

        let data = servicesClient.parseToJson(msg);

        if(data.success == 1){
          servicesUIManager.cancelAddServiceItem(); //Cierra el formulario de nuevo servicio

          let newServ = {
            service_id: data.nid,
            service_name: name,
            service_img: './assets/img/services/' + image,
            service_order: order
          }
          let nodo = servicesUIManager.drawServiceItem(newServ); // creamos un nuevo elemento de servicio con los datos que habían en el form de nuevo servicio
          document.getElementById("services-wrapper").appendChild(nodo);
          servicesUIManager.max_id = order;
        }else {
          alert('Ha ocurrido un error, el registro no pudo ser creado.');
        }
    });
  },


   /**
   * Actualiza un servicio en BD
   */
  update: function (item_id, callback){
    let name = document.getElementById('service-name-' + item_id).value;
    let image = document.getElementById('service-img-' + item_id).value;
    let order = document.getElementById('service-order-' + item_id).value;

    $.ajax({
      method: "POST",
      url: app.service_url + "services.php",
      data: {
        action: "update",
        id: item_id,
        name: name,
        order: order,
        image: image
      }
    }).done(function (msg) {
        app.log(msg);

        let data = servicesClient.parseToJson(msg);

        if(data.success == 1){
          callback();
        }else {
          alert('Ha ocurrido un error, el registro no pudo ser actualizado.');
        }
    });
  },


  /**
   * Elimina un servicio en BD
   */
  delete: function (item_id){
    $.ajax({
      method: "POST",
      url: app.service_url + "services.php",
      data: {
        action: "delete",
        id: item_id
      }
    }).done(function (msg) {
        app.log(msg);

        let data = servicesClient.parseToJson(msg);

        if(data.success == 1){
          let itemToDelete = document.getElementById("serv-item-" + item_id);
          itemToDelete.parentElement.removeChild(itemToDelete);
          alert('Registro eliminado');
        }else {
          alert('Ha ocurrido un error, el registro no pudo ser eliminado.');
        }
    });
  },


  /**
   * Carga al servidor una imagen mediante ajax
   */
  uploadImage: function (item_id = false){ //
    var form = (item_id) ? document.getElementById("service-img-form-" + item_id) : document.getElementById("new-service-img-form");
    var formData = new FormData(form);
    formData.append('action', 'upload_image');
    formData.append('id', (item_id) ? item_id : 0);

    $.ajax({
      url: app.service_url + "services.php",
      type: 'POST',
      data: formData,
      mimeType: "multipart/form-data",
      dataType: 'html',
      contentType: false,
      cache: false,
      processData: false,
      success: function (msg, textStatus, jqXHR) {
        app.log(msg);
        let data = servicesClient.parseToJson(msg);

        if(data.success == 1){
          //data.filename  './assets/img/services/'
          //new-service-image
          let image_elm = (item_id) ? document.getElementById("service-image-" + item_id) : document.getElementById("new-service-image");
          let image_input = (item_id) ? document.getElementById("service-img-" + item_id) : document.getElementById("new-service-img");
          
          image_elm.setAttribute('src', './assets/img/services/' + data.filename);
          image_input.value = data.filename;
        }else {
          alert(data.error);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {

      }
    });
  }
}


/**
 * Maneja el comportamiento de los elementos en pantalla
 */
var servicesUIManager = {
  /**
   * contiene el valor de Id más alto de entre los servicios
   */
  max_id: 0,


  /**
   * construye la lista de servicios y la inyecta en el DOM
   */
  drawServicesList: function(servicesData){
    if(!servicesData){
      return false;
    }

    let services = servicesData.services;
    let servicesCount = servicesData.services_count;

    let wrapper = document.getElementById("services-wrapper");
    wrapper.innerHTML = '';

    services.forEach(function(service) {
      if(servicesUIManager.max_id < service.service_order){
        servicesUIManager.max_id = service.service_order;
      }
      wrapper.appendChild(servicesUIManager.drawServiceItem(service));
    });
  },


  /**
   * construye elemento servicio listo para integrar al DOM
   */
  drawServiceItem: function(serviceItemData){
    if(!serviceItemData){
      return false;
    }

    let id = serviceItemData.service_id;
    let name = serviceItemData.service_name;
    let img = serviceItemData.service_img;
    let order = serviceItemData.service_order; 

    let serviceHtml = /*html*/`
        <td class="service-img text-rose">
          <img id="service-image-${id}" src="${img}" alt="" style="height: 6rem;">
          <div class="service-overlay">
            <label class="text-rose" for="service-img-tmp-${id}">- cambiar imagen -</label>
          </div>
          <form name="service-img-form-${id}" id="service-img-form-${id}" action="" method="post" enctype="multipart/form-data">
            <input id="service-img-tmp-${id}" name="service-img-tmp-${id}" class="input-hidden" type="file" accept="image/*" value="" onchange="servicesClient.uploadImage('${id}')">
          </form>          
          <input id="service-img-${id}" type="hidden" value="${img}">
        </td>
        <td>
          <span id="service-name-lbl-${id}" class="service-name">${name}</span>
          <input id="service-name-${id}" type="text" class="form-control service-input-name" value="${name}"></td>
        <td class="text-center">
          <span id="service-order-lbl-${id}" class="service-order">${order}</span>
          <input id="service-order-${id}" type="number" class="form-control service-input-order" value="${order}"></td>
        </td>
        <td class="text-rose">
          <button type="button" class="service-save btn btn-sm btn-rose pull-right" onclick="servicesUIManager.updateServiceItem('${id}')">Guardar</button>
          <div class="service-options">
            <a class="text-rose hover-effect" onclick="servicesUIManager.editServiceItem('${id}')">
              <i class="material-icons">settings</i>
            </a>
            <a class="text-rose hover-effect" onclick="servicesUIManager.deleteServiceItem('${id}')">
              <i class="material-icons">delete</i>
            </a>
          </div>
        </td>
    `;

    let tr = document.createElement('tr');
    tr.setAttribute('id', 'serv-item-' + id);
    tr.innerHTML = serviceHtml;

    return tr;
  },


  /**
   * inserta en el DOM el formulario para ingresar un nuevo servicio
   */
  addServiceItem: function(){

    if(app.is_creating){
      return false;
    }

    let newId = Number(this.max_id) + 1;

    let addServiceHtml = /*html*/`
      <td class="service-img text-rose">
        <img id="new-service-image" src="./assets/img/services/s-new.png" alt="" style="height: 6rem;">
        <div class="service-overlay" >
          <label class="text-rose" for="new-service-img-tmp">- cambiar imagen -</label>
        </div>
        <form name="new-service-img-form" id="new-service-img-form" action="" method="post" enctype="multipart/form-data">
          <input id="new-service-img-tmp" name="new-service-img-tmp" class="input-hidden" type="file" accept="image/*" value="" onchange="servicesClient.uploadImage()">
        </form>   
        <input id="new-service-img-tmp" class="input-hidden" type="file" accept="image/*" value="">
        <input id="new-service-img" type="hidden" value="s-new.png">
      </td>
      <td>
        <input id="new-service-name" type="text" class="form-control" placeholder="Nombre del servicio"></td>
      <td class="text-center">
        <input id="new-service-order" type="number" class="form-control service-input-order" value="${newId}"></td>
      <td class="text-rose">
        <div class="service-save text-center">
          <a class="text-rose hover-effect" onclick="servicesClient.create()">
            <i class="material-icons">check_circle</i>
          </a>
          <a class="text-rose hover-effect" onclick="servicesUIManager.cancelAddServiceItem()">
            <i class="material-icons">cancel</i>
          </a>
        </div>
      </td>
    `;

    let tr = document.createElement('tr');
    tr.setAttribute('id', 'new-service-item-block');
    tr.setAttribute('class', 'edit new');
    tr.innerHTML = addServiceHtml;

    let wrapper = document.getElementById("services-wrapper");
    wrapper.appendChild(tr);

    app.is_creating =  true;
    document.getElementById("add-serv").classList.add("hide");
  },


  /**
   * cancela la creacion de un nuevo servicio
   */
  cancelAddServiceItem: function(){

    if(app.is_creating){
      let newItemNode = document.getElementById("new-service-item-block");
      newItemNode.parentElement.removeChild(newItemNode);

      app.is_creating =  false;
      document.getElementById("add-serv").classList.remove("hide");
    }
  },


  /**
   * coloca un elemento servicio en modo de edición
   */
  editServiceItem: function(service_id){
    if(!service_id){
      return false;
    }

    let sItem = document.getElementById("serv-item-" + service_id);
    sItem.classList.add("edit");    

    document.getElementById("service-name-" + service_id).focus();
  },

  /**
   * Guarda los cambios del modo edicion en la bd y devueve el elemento al modo solo lectura
   */
  updateServiceItem: function(service_id){
    if(!service_id){
      return false;
    }    

    let sItem = document.getElementById("serv-item-" + service_id);
    sItem.classList.remove("edit");    

    servicesClient.update(service_id, function(){
      let name = document.getElementById('service-name-' + service_id).value;
      let order = document.getElementById('service-order-' + service_id).value;

      document.getElementById('service-name-lbl-' + service_id).innerText = name;
      document.getElementById('service-order-lbl-' + service_id).innerText = order;
    });
  },


  /**
   * Elimina un elemento servicio
   */
  deleteServiceItem: function(service_id){
    if(!service_id){
      return false;
    }

    if (window.confirm("Está seguro de que desea eliminar este elemento?")) {
      servicesClient.delete(service_id);
    }
  },

}