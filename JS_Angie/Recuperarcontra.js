
        const formulario = document.getElementById("formularioRecuperar");

        const mensaje = document.getElementById("mensaje");

        formulario.addEventListener("submit", function (event) {

            event.preventDefault();

            let correo = document.getElementById("correo").value;

           
            if (correo === "") {

                mensaje.innerHTML = "Por favor ingresa un correo.";
                mensaje.style.color = "red";

                return;
            }

            mensaje.innerHTML = "Se envió un enlace de recuperación a tu correo.";
            mensaje.style.color = "green";

            formulario.reset();

        });
