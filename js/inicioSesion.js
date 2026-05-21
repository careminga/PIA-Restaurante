console.log("hola");
const user = {
    "email": "admin@admin.com",
    "password": "admin123",
    "user": "admin"
}

const formulario = document.getElementById("inicioSesion");

formulario.addEventListener("submit",function(event){
    event.preventDefault();
    
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    if (user.email === email && user.password === password){
        window.location.href="inadministrador.html"
        sessionStorage.setItem("user", user.user).
    return
    }
    formulario.reset();
    return alert("usuario no encontrado");
    console.log("entre");
})