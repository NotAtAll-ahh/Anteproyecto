(function () {
    const profileLink = document.querySelector(".user-icon-circle");
    if (!profileLink) {
        return;
    }

    const currentScript = document.currentScript;
    const scriptUrl = currentScript ? new URL(currentScript.src, window.location.href) : new URL(window.location.href);
    const appBase = scriptUrl.pathname.replace(/\/api-entradas\/shared-header-user\.js$/, "");
    const API_BASE = `${appBase}/api-entradas/public/api`;
    const PUBLIC_BASE = `${appBase}/api-entradas/public`;

    if (profileLink.getAttribute("href")) {
        profileLink.setAttribute("href", `${appBase}/api-entradas/perfil.html`);
    }

    function renderDefaultIcon() {
        profileLink.innerHTML = '<i class="fas fa-user"></i>';
    }

    function renderUserPhoto(usuario) {
        if (!usuario || !usuario.foto_perfil) {
            renderDefaultIcon();
            return;
        }

        const image = document.createElement("img");
        image.src = PUBLIC_BASE + usuario.foto_perfil;
        image.alt = usuario.nombre ? `Foto de perfil de ${usuario.nombre}` : "Foto de perfil";
        image.style.width = "100%";
        image.style.height = "100%";
        image.style.objectFit = "cover";
        image.style.borderRadius = "50%";
        image.loading = "lazy";

        image.addEventListener("error", renderDefaultIcon, { once: true });

        profileLink.textContent = "";
        profileLink.appendChild(image);
    }

    fetch(`${API_BASE}/sesion`, {
        method: "GET",
        credentials: "include"
    })
        .then(function (response) {
            return response.json();
        })
        .then(function (session) {
            if (!session.logueado || !session.usuario_id) {
                renderDefaultIcon();
                return null;
            }

            return fetch(`${API_BASE}/usuarios/${session.usuario_id}`, {
                method: "GET",
                credentials: "include"
            });
        })
        .then(function (response) {
            if (!response) {
                return null;
            }
            return response.json();
        })
        .then(function (data) {
            const usuario = data && data.data ? data.data : data;
            renderUserPhoto(usuario);
        })
        .catch(function () {
            renderDefaultIcon();
        });
}());
