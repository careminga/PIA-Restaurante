console.log("Hola")
const user = [
    {
        "email": "admin@admin.com",
        "passwors" : "ad123",
        "user" : "admin"
    },
    {
        "email": "admin@estu.com",
        "passwors" : "es123",
        "user" : "est"
    },
    {
        "email": "admin@maes.com",
        "passwors" : "mae123",
        "user" : "maes"
    }

]

function iniciosesion(email,passwor){
    for (let index = 0; index < user.length; index++){
        const element = user[index];
        console.log("elemento", element.email);
        console.log("elemento", element.passwor);
        console.log("elemento", element.user);

        
    }
}

const formulario = document.getElementById("iniciarsesion");

formulario.addEventListener("submit", function (event) {

    event.preventDefault();

    let correo = document.getElementById("correo").value;
    let password = document.getElementById("password").value;

    if (user.email === correo && user.password === password) {

        sessionStorage.setItem("user", user.user);

        window.location.href = "./tablatiposR.html";

        return;
    }

});