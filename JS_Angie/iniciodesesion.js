let formulario = document.getElementById("IniciarSesion");
formulario.addEventListener("Submit", function (event){
    event.preventDefault();
    console.log("No se recargo", formulario)
    let email = document.getElementById("email").value
    let password = document.getElementById("password").value
})

function iniciarSesion(){

    const correo = document.getElementById("correo").value;
    const password = document.getElementById("password").value;

    if(correo === "admin@gmail.com" && password === "123"){

        localStorage.setItem("tipoUsuario", "admin");

        window.location.href = "inadministrador.html";

    }

    else if(correo === "estudiante@gmail.com" && password === "123"){

        localStorage.setItem("tipoUsuario", "estudiante");

        window.location.href = "inicio.html";

    }

    else{

        alert("Correo o contraseña incorrectos");

    }
    function cerrarSesion(){

    localStorage.removeItem("tipoUsuario");

    window.location.href = "login.html";

}
const usuario = localStorage.getItem("tipoUsuario");

if(usuario){

    console.log("Hay sesión iniciada");

}
else{

    console.log("No hay sesión");

}

}