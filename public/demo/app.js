// Funciones compartidas para el demo de Nexus E-commerce

const API_URL = window.location.origin + '/api';

// Manejo de autenticacion
const Auth = {
    getToken() {
        return localStorage.getItem('nexus_token');
    },
    
    setToken(token) {
        localStorage.setItem('nexus_token', token);
    },
    
    clearToken() {
        localStorage.removeItem('nexus_token');
        localStorage.removeItem('nexus_user');
    },
    
    getUser() {
        const user = localStorage.getItem('nexus_user');
        return user ? JSON.parse(user) : null;
    },
    
    setUser(user) {
        localStorage.setItem('nexus_user', JSON.stringify(user));
    },
    
    isAuthenticated() {
        return !!this.getToken();
    }
};

// Funciones para hacer peticiones a la API
const API = {
    async request(endpoint, options = {}) {
        const token = Auth.getToken();
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            ...options.headers
        };
        
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }
        
        try {
            const response = await fetch(API_URL + endpoint, {
                ...options,
                headers
            });
            
            const data = await response.json();
            
            return {
                ok: response.ok,
                status: response.status,
                data
            };
        } catch (error) {
            return {
                ok: false,
                status: 0,
                data: { message: error.message }
            };
        }
    },
    
    async get(endpoint) {
        return this.request(endpoint, { method: 'GET' });
    },
    
    async post(endpoint, body) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(body)
        });
    },
    
    async put(endpoint, body) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(body)
        });
    },
    
    async delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }
};

// Funciones de utilidad
const Utils = {
    showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        
        const container = document.querySelector('.content');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
            setTimeout(() => alertDiv.remove(), 5000);
        }
    },
    
    formatPrice(price) {
        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN'
        }).format(price);
    },
    
    showCode(title, code) {
        const codeViewer = document.getElementById('codeViewer');
        if (codeViewer) {
            codeViewer.innerHTML = `
                <h4>${title}</h4>
                <pre>${JSON.stringify(code, null, 2)}</pre>
            `;
        }
    },
    
    requireAuth() {
        if (!Auth.isAuthenticated()) {
            Utils.showAlert('Debes iniciar sesion primero', 'error');
            setTimeout(() => {
                window.location.href = 'auth.html';
            }, 2000);
            return false;
        }
        return true;
    }
};

// Actualizar UI segun estado de autenticacion
function updateAuthUI() {
    const user = Auth.getUser();
    const authInfo = document.getElementById('authInfo');
    
    if (authInfo && user) {
        authInfo.innerHTML = `
            <div class="user-info">
                <strong>Usuario:</strong> ${user.nombre_completo || user.email}
                <button onclick="logout()" class="btn btn-danger" style="float: right; padding: 5px 15px;">
                    Cerrar Sesion
                </button>
            </div>
        `;
    }
}

// Funcion de logout global
async function logout() {
    await API.post('/logout');
    Auth.clearToken();
    Utils.showAlert('Sesion cerrada exitosamente', 'success');
    setTimeout(() => {
        window.location.href = 'index.html';
    }, 1500);
}

// Inicializar al cargar la pagina
document.addEventListener('DOMContentLoaded', () => {
    updateAuthUI();
});
