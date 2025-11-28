// ======================================================
// CONFIGURACIÓN
// ======================================================
const API = "http://127.0.0.1:8000/usuarios";

// ======================================================
// LOGIN
// ======================================================
async function login() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const msg = document.getElementById("login-msg");

    msg.textContent = "";

    const res = await fetch(API + "/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password })
    });

    const data = await res.json();

    if (!res.ok) {
        msg.textContent = data.error || "Error al iniciar sesión";
        msg.style.color = "red";
        return;
    }

    localStorage.setItem("token", data.token);
    localStorage.setItem("role", data.user.role);

    msg.textContent = "Login exitoso ✔";
    msg.style.color = "green";

    if (data.user.role === "administrador") {
        window.location.href = "admin.html";
    } else {
        window.location.href = "home.html";
    }
}

// ======================================================
// LOGOUT
// ======================================================
function logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("role");
    window.location.href = "index.html";
}

// ======================================================
// REGISTRAR USUARIO (solo admin)
// ======================================================
async function registerUser() {
    const name = document.getElementById("reg-name").value;
    const email = document.getElementById("reg-email").value;
    const password = document.getElementById("reg-pass").value;
    const role = document.getElementById("reg-role").value;
    const msg = document.getElementById("reg-msg");

    msg.textContent = "";

    const res = await fetch(API + "/register", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + localStorage.getItem("token")
        },
        body: JSON.stringify({ name, email, password, role })
    });

    const data = await res.json();

    if (!res.ok) {
        msg.textContent = data.error || "Error al registrar";
        return;
    }

    msg.textContent = "Usuario creado ✔";
    msg.style.color = "green";
    getUsers();
}

// ======================================================
// LISTAR USUARIOS
// ======================================================
async function getUsers() {
    const body = document.getElementById("users-table-body");

    const res = await fetch(API + "/all", {
        headers: { "Authorization": "Bearer " + localStorage.getItem("token") }
    });

    const data = await res.json();

    if (!res.ok) {
        body.innerHTML = `<tr><td colspan="5">${data.error}</td></tr>`;
        return;
    }

    body.innerHTML = "";

    data.forEach(u => {
        body.innerHTML += `
            <tr>
                <td>${u.id}</td>
                <td>${u.name}</td>
                <td>${u.email}</td>
                <td>${u.role}</td>
                <td>
                    <button onclick="openEdit(${u.id}, '${u.name}', '${u.email}')">Editar</button>
                    <button onclick="openRole(${u.id})">Cambiar Rol</button>
                    
                </td>
            </tr>
        `;
    });
}

// ======================================================
// MODAL EDITAR
// ======================================================
function openEdit(id, name, email) {
    document.getElementById("edit-box").style.display = "block";
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-email").value = email;
}

async function updateUser() {
    const id = document.getElementById("edit-id").value;
    const name = document.getElementById("edit-name").value;
    const email = document.getElementById("edit-email").value;

    const res = await fetch(`${API}/update/${id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + localStorage.getItem("token")
        },
        body: JSON.stringify({ name, email })
    });

    const data = await res.json();
    alert(data.message || data.error);

    document.getElementById("edit-box").style.display = "none";
    getUsers();
}

// ======================================================
// MODAL CAMBIAR ROL
// ======================================================
function openRole(id) {
    document.getElementById("role-box").style.display = "block";
    document.getElementById("role-id").value = id;
}

async function updateRole() {
    const id = document.getElementById("role-id").value;
    const role = document.getElementById("new-role").value;

    const res = await fetch(`${API}/update/${id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Authorization": "Bearer " + localStorage.getItem("token")
        },
        body: JSON.stringify({ role })
    });

    const data = await res.json();
    alert(data.message || data.error);

    document.getElementById("role-box").style.display = "none";
    getUsers();
}

