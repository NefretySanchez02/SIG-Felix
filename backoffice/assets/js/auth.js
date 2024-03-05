document.getElementById('init-login').addEventListener('click', init);

function init(){
  user = document.getElementById('user').value;
  pwd = document.getElementById('pwd').value;

  if(user.length < 5 || pwd.length < 6){
    alert('El usuario o la contraseña no tienen la longitud mínima necesaria.');
    return false;
  }

  $.ajax({
    method: "POST",
    url: "services/auth.php",
    data: {
      username: user,
      password: pwd
    }
  }).done(function (rawData) {
      var sub_msg = rawData.substring(rawData.indexOf("{"), rawData.lastIndexOf("}") + 1);
      console.log(sub_msg)
      let data =  JSON.parse(sub_msg);
  
      if(data.success == 1){
        window.location.href = "./";
      } else {
        alert(data.error);
      }
  });
}