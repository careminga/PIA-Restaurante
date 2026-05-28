document.addEventListener("DOMContentLoaded", function () {
    let rol = sessionStorage.getItem("userRole");

    if (!rol) {
        window.location.href = "./iniciodesesion.html";
        alert("No haz iniciado sesión")
    } else if (rol === "est") {
        window.location.href = "./inusuario.html";
        alert("No eres administrador")
    }
});

const btnenviar = document.getElementById("btnenviar");
btnenviar.addEventListener("click", function() {
});