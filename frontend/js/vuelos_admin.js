const API_V = "http://127.0.0.1:9000";

// VALIDAR SESIÓN

if (!localStorage.getItem("token") || localStorage.getItem("role") !== "administrador") {
    alert("No autorizado");
    window.location.href = "index.html";
}

// LOGOUT

function logout() {
    localStorage.clear();
    window.location.href = "index.html";
}

// NAVES

async function loadNaves() {
    const res = await fetch(API_V + "/naves/all");
    const data = await res.json();

    const t = document.getElementById("tabla-naves");
    t.innerHTML = "";

    data.forEach(n => {
        t.innerHTML += `
            <tr>
                <td>${n.id}</td>
                <td>${n.nombre}</td>
                <td>${n.capacidad}</td>
                <td>${n.tipo}</td>
                <td>
                    <button onclick="deleteNave(${n.id})">Eliminar</button>
                </td>
            </tr>
        `;
    });

    // Llenar select de naves para crear vuelos
    const select = document.getElementById("v-nave_id");
    select.innerHTML = "";
    data.forEach(n => {
        select.innerHTML += `<option value="${n.id}">${n.nombre}</option>`;
    });
}

async function createNave() {
    const nombre = document.getElementById("n-nombre").value;
    const capacidad = document.getElementById("n-capacidad").value;
    const tipo = document.getElementById("n-tipo").value;

    const res = await fetch(API_V + "/naves/create", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nombre, capacidad, tipo })
    });

    const data = await res.json();
    alert(data.message || data.error);

    loadNaves();
    return false;
}

async function deleteNave(id) {
    if (!confirm("¿Eliminar nave?")) return;

    const res = await fetch(API_V + "/naves/delete/" + id, {
        method: "DELETE"
    });

    const data = await res.json();
    alert(data.message || data.error);

    loadNaves();
}

// VUELOS

async function loadVuelos() {
    const res = await fetch(API_V + "/vuelos/all");
    const data = await res.json();

    const t = document.getElementById("tabla-vuelos");
    t.innerHTML = "";

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
                <td>
                    <button onclick="deleteVuelo(${v.id})">Eliminar</button>
                </td>
            </tr>
        `;
    });
}

async function createVuelo() {
    const body = {
        origen: document.getElementById("v-origen").value,
        destino: document.getElementById("v-destino").value,
        fecha: document.getElementById("v-fecha").value,
        hora: document.getElementById("v-hora").value,
        precio: document.getElementById("v-precio").value,
        nave_id: document.getElementById("v-nave_id").value,
    };

    const res = await fetch(API_V + "/vuelos/create", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body)
    });

    const data = await res.json();
    alert(data.message || data.error);

    loadVuelos();
    return false;
}

async function deleteVuelo(id) {
    if (!confirm("¿Eliminar vuelo?")) return;

    const res = await fetch(API_V + "/vuelos/delete/" + id, {
        method: "DELETE"
    });

    const data = await res.json();
    alert(data.message || data.error);

    loadVuelos();
}

// Al iniciar
loadNaves();
loadVuelos();
