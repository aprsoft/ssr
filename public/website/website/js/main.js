
document.getElementById("year").textContent = new Date().getFullYear();

const nombre = document.getElementById("nombre");
const empresa = document.getElementById("empresa");
const email = document.getElementById("email");
const telefono = document.getElementById("telefono");
const mensaje = document.getElementById("mensaje");
const mensajeContador = document.getElementById("mensajeContador");
const form = document.getElementById("contactForm");
const statusMessage = document.getElementById("statusMessage");

// Validaciones
function validarNombre() {
    const regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{1,30}$/;
    if(regex.test(nombre.value.trim())) { nombre.classList.add("valid"); nombre.classList.remove("invalid"); return true; }
    else { nombre.classList.add("invalid"); nombre.classList.remove("valid"); return false; }
}

function validarEmpresa() {
    const val = empresa.value.trim();
    if(val.length > 0 && val.length <= 30){ empresa.classList.add("valid"); empresa.classList.remove("invalid"); return true; }
    else { empresa.classList.add("invalid"); empresa.classList.remove("valid"); return false; }
}

function validarEmail() {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(regex.test(email.value.trim())){ email.classList.add("valid"); email.classList.remove("invalid"); return true; }
    else { email.classList.add("invalid"); email.classList.remove("valid"); return false; }
}

function validarTelefono() {
    const regex = /^[0-9]{1,8}$/;
    if(regex.test(telefono.value.trim())){ telefono.classList.add("valid"); telefono.classList.remove("invalid"); return true; }
    else { telefono.classList.add("invalid"); telefono.classList.remove("valid"); return false; }
}

function validarMensaje() {
    const max = 100;
    const len = mensaje.value.length;
    mensajeContador.textContent = `${max - len} caracteres restantes`;
    if(len > 0 && len <= max){ mensaje.classList.add("valid"); mensaje.classList.remove("invalid"); return true; }
    else { mensaje.classList.add("invalid"); mensaje.classList.remove("valid"); return false; }
}

// Filtrado en tiempo real
nombre.addEventListener("input", () => {
    nombre.value = nombre.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g,'');
    validarNombre();
});
telefono.addEventListener("input", () => {
    telefono.value = telefono.value.replace(/[^0-9]/g,'');
    validarTelefono();
});
empresa.addEventListener("input", validarEmpresa);
email.addEventListener("input", validarEmail);
mensaje.addEventListener("input", validarMensaje);

form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const valid = validarNombre() && validarEmpresa() && validarEmail() && validarTelefono() && validarMensaje();
    if(!valid){
        statusMessage.textContent = "❌ Corrige los campos en rojo.";
        statusMessage.className = "status-message status-error";
        statusMessage.style.display = "block";
        return;
    }

    statusMessage.textContent = "Enviando...";
    statusMessage.className = "status-message";
    statusMessage.style.display = "block";

    const formData = new FormData(form);
    try {
        const res = await fetch(form.action, { method: "POST", body: formData });
        const text = await res.text();
        if(text.includes("ok")){
            statusMessage.textContent = "✅ Mensaje enviado correctamente.";
            statusMessage.className = "status-message status-success";
            form.reset();
            mensajeContador.textContent = "100 caracteres restantes";
            [nombre, empresa, email, telefono, mensaje].forEach(el => el.classList.remove("valid"));
        } else {
            statusMessage.textContent = "❌ Error al enviar el mensaje.";
            statusMessage.className = "status-message status-error";
        }
    } catch(err){
        statusMessage.textContent = "❌ Error de conexión.";
        statusMessage.className = "status-message status-error";
    }
});
