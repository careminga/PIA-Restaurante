const usuarios = [
    {
        "email": "admin@admin.com",
        "password": "admin123",
        "role": "admin"  
    },
    {
        "email": "user@user.com",
        "password": "user123",
        "role": "est"  
    }
];

const formulario = document.getElementById("inicioSesion");

formulario.addEventListener("submit", function(event) {
    event.preventDefault();
    
    let emailInput = document.getElementById("email").value;
    let passwordInput = document.getElementById("password").value;
    
    let usuarioEncontrado = usuarios.find(u => u.email === emailInput && u.password === passwordInput);

    if (usuarioEncontrado) {
        sessionStorage.setItem("userRole", usuarioEncontrado.role);
        
        if (usuarioEncontrado.role === "admin") {
            window.location.href = "inadministrador.html";
        } else if (usuarioEncontrado.role === "est") {
            window.location.href = "inusuario.html";
        }
        return;
    }
    
    formulario.reset();
    alert("Usuario o contraseña incorrectos");
});