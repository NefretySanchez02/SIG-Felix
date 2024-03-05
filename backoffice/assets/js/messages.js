
/**
 * Maneja las peticiones Ajax
 */
var messagesClient = {
  init: function (){
    messagesClient.list();
    setInterval(() => {
      messagesClient.list();
    }, 30000);
  },

  /**
   * Obtiene mediante consulta un arreglo con todos los mensajes en BD
   */
  list: function (){
    $.ajax({
      method: "GET",
      url: application.service_url + "messages.php",
      data: {action: "list"}
    }).done(function (msg) {
        application.log(msg);

        let data = application.parseJson(msg);

        if(data.success == 1){
          messagesUIManager.drawList(data);
        }
    });
  },

  /**
   * Obtiene mediante consulta un mensajes desde BD
   */
   get: function (item_id, callback){
    $.ajax({
      method: "GET",
      url: application.service_url + "messages.php",
      data: {
        action: "get",
        mid: item_id
      }
    }).done(function (msg) {
        application.log(msg);

        let data = application.parseJson(msg);

        if(data.success == 1){
          callback(data.messaje);
        }
    });
  },

   /**
   * Actualiza el estado del mensaje en BD
   */
  markAsRead: function (item_id, callback){ 
    $.ajax({
      method: "POST",
      url: application.service_url + "messages.php",
      data: {
        action: "markasviewed",
        mid: item_id
      }
    }).done(function (msg) {
        application.log(msg);

        let data = application.parseJson(msg);

        if(data.success == 1){
          callback();
        }


    });
  }


}


/**
 * Maneja el comportamiento de los elementos en pantalla
 */
var messagesUIManager = {  

  /**
   * construye la lista de servicios y la inyecta en el DOM
   */
  drawList: function(dataset){
    if(!dataset){
      return false;
    }

    let messages = dataset.messages;

    let wrapper = document.getElementById("messages-wrapper");
    wrapper.innerHTML = '';

    messages.forEach(function(msg) {
      wrapper.appendChild(messagesUIManager.drawItem(msg));
    });
  },


  /**
   * construye elemento servicio listo para integrar al DOM
   */
  drawItem: function(itemData){
    if(!itemData){
      return false;
    }

    let id = itemData.id;
    let name = itemData.nombre;
    let email = itemData.email;
    let phone = itemData.telefono;
    let subject = itemData.asunto; 
    let datetime = itemData.datetime;
    let business = itemData.empresa; 
    let status = itemData.estado == 'PENDIENTE' ? 'pendiente' : ''; 

    let itemHtml = /*html*/`        
        <td>
          <span class="service-name">${datetime}</span>
        </td>
        <td>
          <span class="d-block">${name}</span>
          <span class="text-info d-block" style="line-height: 1">${email}</span>
          <span class="text-info" style="line-height: 1">${phone}</span>
          <span class="text-info" style="line-height: 1">${business}</span>
        </td>
        <td>
          <span class="service-order">${subject}</span>
        </td>

        <td class="text-info">
          <div class="">
            <a class="text-info hover-effect" onclick="messagesUIManager.showItem('${id}')">
              <i class="material-icons">visibility</i>
            </a>
          </div>
        </td>
    `;

    let tr = document.createElement('tr');
    tr.setAttribute('id', 'serv-item-' + id);
    tr.setAttribute('class', status);
    tr.innerHTML = itemHtml;

    return tr;
  },


   

  /**
   * Muestra un modal con el detalle del mensaje y lo marca como leido en la bd
   */
  showItem: function(id){
    if(!id){
      return false;
    } 

    messagesClient.get(id, function( data ){
      document.getElementById('item-message-subject').innerText = data.asunto;
      document.getElementById('item-message-name').innerText = data.nombre;
      document.getElementById('item-message-email').innerText = data.email;
      document.getElementById('item-message-phone').innerText = data.telefono;
      document.getElementById('item-message-business').innerText = data.empresa;
      document.getElementById('item-message-date').innerText = data.datetime;
      document.getElementById('item-message-txt').innerText = data.mensaje;

      document.getElementById('message-datail').classList.add("d-block");
    });

    messagesClient.markAsRead(id, messagesClient.list);
  },


  /**
   * Guarda los cambios del modo edicion en la bd y devueve el elemento al modo solo lectura
   */
   hideItemDetailModal: function(id){
    document.getElementById('message-datail').classList.remove("d-block");
  }

  

}