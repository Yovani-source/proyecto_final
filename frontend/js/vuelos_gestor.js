const API_V = "http://127.0.0.1:9000";

// ====================
// VALIDAR SESIÓN
// ====================
if (!localStorage.getItem("token") || localStorage.getItem("role") !== "gestor") {
    alert("No autorizado");
    window.location.href = "index.html";
}

// ====================
// LOGOUT
// ====================
function logout() {
    localStorage.clear();
    window.location.href = "index.html";
}

// ===============================
// BUSCAR VUELOS
// ===============================
async function buscarVuelos() {
    const origen = document.getElementById("b-origen").value;
    const destino = document.getElementById("b-destino").value;
    const fecha = document.getElementById("b-fecha").value;

    const params = new URLSearchParams();

    if (origen) params.append("origen", origen);
    if (destino) params.append("destino", destino);
    if (fecha) params.append("fecha", fecha);

    const res = await fetch(API_V + "/vuelos/search?" + params.toString());
    const data = await res.json();

    const t = document.getElementById("tabla-vuelos");
    t.innerHTML = "";

    if (data.length === 0) {
        t.innerHTML = `<tr><td colspan="8">No hay vuelos disponibles</td></tr>`;
        return false;
    }

    data.forEach(v => {
        t.innerHTML += `
            <tr>
                <td>${v.id}</td>
                <td>${v.origen}</td>
                <td>${v.destino}</td>
                <td>${v.fecha}</td>
                <td>${v.hora}</td>
                <td>${v.precio}</td>
                <td>${v.nave.nombre}</td>
                <td><button onclick="reservar(${v.id})">Reservar</button></td>
            </tr>
        `;
    });

    return false;
}

// ===============================
// CREAR RESERVA
// ===============================
async function reservar(vuelo_id) {
    const res = await fetch(API_V + "/reservas/create", {
        method: "POST",
        headers: { 
            "Content-Type": "application/json",
            "Authorization": "Bearer " + localStorage.getItem("token")
        },
        body: JSON.stringify({ vuelo_id })
    });

    const data = await res.json();
    alert(data.message || data.error);

    loadReservas();
}

// ===============================
// LISTAR RESERVAS DEL GESTOR
// ===============================
async function loadReservas() {
    const res = await fetch(API_V + "/reservas/my", {
        headers: { "Authorization": "Bearer " + localStorage.getItem("token") }
    });

    const data = await res.json();
    const t = document.getElementById("tabla-reservas");
    t.innerHTML = "";

    if (!data.length) {
        t.innerHTML = `<tr><td colspan="7">No tienes reservas</td></tr>`;
        return;
    }

    data.forEach(r => {
        t.innerHTML += `
            <tr>
                <td>${r.id}</td>
                <td>${r.vuelo.id}</td>
                <td>${r.vuelo.fecha}</td>
                <td>${r.vuelo.hora}</td>
                <td>${r.vuelo.origen}</td>
                <td>${r.vuelo.destino}</td>
                <td>
                    <button onclick="cancelar(${r.id})">Cancelar</button>
                </td>
            </tr>
        `;
    });
}

// ===============================
// CANCELAR RESERVA
// ===============================
async function cancelar(id) {
    if (!confirm("¿Cancelar reserva?")) return;

    const res = await fetch(API_V + "/reservas/delete/" + id, {
        method: "DELETE",
        headers: {
            "Authorization": "Bearer " + localStorage.getItem("token")
        }
    });

    const data = await res.json();
    alert(data.message || data.error);

    loadReservas();
}

// Al cargar
loadReservas();
