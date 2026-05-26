let formulario = document.getElementById("IniciarSesion");
//function enviar() { }
formulario.addEventListener("Submit", function (event){
    event.preventDefault(); //Prevenga el comportamiento por defecto
    console.log("No se recargo", formulario)
    let email = document.getElementById("email").value
    let password = document.getElementById("password").value
    //console.log("email", email);
    //Console.log("password", password);
})

function iniciarSesion(){

    const correo = document.getElementById("correo").value;
    const password = document.getElementById("password").value;

    // ADMIN
    if(correo === "admin@gmail.com" && password === "123"){

        localStorage.setItem("tipoUsuario", "admin");

        window.location.href = "admin.html";

    }

    // ESTUDIANTE
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
//*Let, console, NOOOO=VAR
//Las funciones reciben parametros=even