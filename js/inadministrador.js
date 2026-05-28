document.addEventListener("DOMContentLoaded", function () {
    let rol = sessionStorage.getItem("userRole");

    if (!rol) {
        alert("No has iniciado sesión");
        window.location.href = "./iniciodesesion.html";
        return;
    } 
    
    if (rol === "est") {
        alert("No eres administrador");
        window.location.href = "./inusuario.html";
        return;
    }
});