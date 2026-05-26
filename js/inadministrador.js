document.addEventListener("DOMContentLoaded", function () {
    let rol = sessionStorage.getItem("userRole");

    if (!rol) {
        window.location.href = "./iniciodesesion.html";
    } else if (rol === "est") {
        window.location.href = "./inusuario.html";
    }
});