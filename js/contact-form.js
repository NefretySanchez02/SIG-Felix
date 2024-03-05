const formulario = document.querySelector("#contact-form");
const urlDesktop = "https://api.whatsapp.com/send?phone=+573008271769&text=";

function sendForm() {
  if (
    document.getElementById("name-form").value.trim().length === 0 ||
    document.getElementById("address-form").value.trim().length === 0 ||
    document.getElementById("phone-form").value.trim().length === 0 ||
    document.getElementById("message-form").value.trim().length === 0
  ) {
    alert("Debes completar los campos para continuar");
    return false;
  }
  let name = document.getElementById("name-form").value;
  let address = document.getElementById("address-form").value;
  let phone = document.getElementById("phone-form").value;
  let message = document.getElementById("message-form").value;
  let mensaje =
    "*Formulario de Pagina SIGCTG*%0aNombre:" +
    name +
    "%0aDireccion:" +
    address +
    "%0aTelefono:" +
    phone +
    "%0aMensaje:" +
    message +
    "";
  window.open(urlDesktop + mensaje, "_blank");
  window.location.reload()
}
