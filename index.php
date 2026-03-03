<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CRUD Player</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #0b1220; }
    .card { background: #121a2b; border: 1px solid rgba(255,255,255,.08); }
    .table { --bs-table-bg: transparent; --bs-table-color: #e6e9ef; }
    .table thead th { background: #0f172a; color: #aab3c5; font-size: 13px; text-transform: uppercase; letter-spacing: .3px; }
    .table tbody tr:hover td { background: rgba(255,255,255,.03); }
    .table td, .table th { border-color: rgba(255,255,255,.07); vertical-align: middle; }
    h1, .text-muted-custom { color: #e6e9ef; }
    .text-muted-custom { opacity: .75; }
  </style>
</head>
<body class="py-5">
<div class="container">
  <div class="card rounded-4 p-4 shadow-lg">

    <!-- Topbar -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="fs-4 fw-semibold mb-1">Players — CRUD</h1>
        <p class="text-muted-custom mb-0">
          Tabla: <span class="badge bg-secondary">app_db.player</span>
        </p>
      </div>
      <button class="btn btn-success btn-sm px-3" data-bs-toggle="modal" data-bs-target="#modalCrear">
        <i class="bi bi-plus-lg me-1"></i> Nuevo jugador
      </button>
    </div>

    <!-- Tabla -->
    <div class="table-responsive rounded-3 overflow-hidden">
      <table class="table table-hover mb-0" id="tablaPlayers">
        <thead>
          <tr>
            <th>Code</th>
            <th>Nombre</th>
            <th>Dorsal</th>
            <th>Posición</th>
            <th style="width:180px;">Acciones</th>
          </tr>
        </thead>
        <tbody id="tbody">
          <tr><td colspan="5" class="text-center text-secondary py-4">Cargando...</td></tr>
        </tbody>
      </table>
    </div>

  </div>
</div>

<!-- ── MODAL CREAR ─────────────────────────────────────── -->
<div class="modal fade" id="modalCrear" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">➕ Nuevo jugador</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Code</label>
          <input type="text" class="form-control bg-dark text-light border-secondary" id="crear-code">
        </div>
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" class="form-control bg-dark text-light border-secondary" id="crear-name">
        </div>
        <div class="mb-3">
          <label class="form-label">Dorsal</label>
          <input type="number" class="form-control bg-dark text-light border-secondary" id="crear-dorsal">
        </div>
        <div class="mb-3">
          <label class="form-label">Posición</label>
          <input type="text" class="form-control bg-dark text-light border-secondary" id="crear-position">
        </div>
      </div>
      <div class="modal-footer border-secondary">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-success" onclick="crearJugador()">Crear</button>
      </div>
    </div>
  </div>
</div>

<!-- ── MODAL EDITAR ─────────────────────────────────────── -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">✏️ Editar jugador</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editar-code">
        <div class="mb-3">
          <label class="form-label">Code</label>
          <input type="text" class="form-control bg-dark text-light border-secondary" id="editar-code-display" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" class="form-control bg-dark text-light border-secondary" id="editar-name">
        </div>
        <div class="mb-3">
          <label class="form-label">Dorsal</label>
          <input type="number" class="form-control bg-dark text-light border-secondary" id="editar-dorsal">
        </div>
        <div class="mb-3">
          <label class="form-label">Posición</label>
          <input type="text" class="form-control bg-dark text-light border-secondary" id="editar-position">
        </div>
      </div>
      <div class="modal-footer border-secondary">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" onclick="guardarEdicion()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- ── MODAL VER ─────────────────────────────────────────── -->
<div class="modal fade" id="modalVer" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">👁️ Ver jugador</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label text-secondary">Code</label>
          <input type="text" class="form-control bg-dark text-light border-secondary" id="ver-code" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label text-secondary">Nombre</label>
          <input type="text" class="form-control bg-dark text-light border-secondary" id="ver-name" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label text-secondary">Dorsal</label>
          <input type="text" class="form-control bg-dark text-light border-secondary" id="ver-dorsal" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label text-secondary">Posición</label>
          <input type="text" class="form-control bg-dark text-light border-secondary" id="ver-position" disabled>
        </div>
      </div>
      <div class="modal-footer border-secondary">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API = 'api/players.php';

// ── Cargar tabla ──────────────────────────────────────────
async function cargarJugadores() {
  const res  = await fetch(API);
  const data = await res.json();
  const tbody = document.getElementById('tbody');

  if (!data.length) {
    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-secondary py-4">Sin jugadores</td></tr>';
    return;
  }

  tbody.innerHTML = data.map(p => `
    <tr>
      <td class="font-monospace">${p.code}</td>
      <td>${p.name}</td>
      <td>${p.dorsal}</td>
      <td><span class="badge bg-secondary">${p.position}</span></td>
      <td>
        <button class="btn btn-sm btn-outline-info me-1" onclick="verJugador('${p.code}')" title="Ver">
          <i class="bi bi-eye"></i>
        </button>
        <button class="btn btn-sm btn-outline-primary me-1" onclick="abrirEditar('${p.code}')" title="Editar">
          <i class="bi bi-pencil"></i>
        </button>
        <button class="btn btn-sm btn-outline-danger" onclick="borrarJugador('${p.code}')" title="Borrar">
          <i class="bi bi-trash"></i>
        </button>
      </td>
    </tr>`).join('');
}

// ── Crear ─────────────────────────────────────────────────
async function crearJugador() {
  const body = {
    code:     document.getElementById('crear-code').value.trim(),
    name:     document.getElementById('crear-name').value.trim(),
    dorsal:   document.getElementById('crear-dorsal').value.trim(),
    position: document.getElementById('crear-position').value.trim()
  };
  const res = await fetch(API, { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(body) });
  const data = await res.json();
  alert(data.message || data.error);
  if (res.ok) {
    bootstrap.Modal.getInstance(document.getElementById('modalCrear')).hide();
    cargarJugadores();
  }
}

// ── Abrir modal editar ────────────────────────────────────
async function abrirEditar(code) {
  const res  = await fetch(`${API}?code=${code}`);
  const p    = await res.json();
  document.getElementById('editar-code').value         = p.code;
  document.getElementById('editar-code-display').value = p.code;
  document.getElementById('editar-name').value         = p.name;
  document.getElementById('editar-dorsal').value       = p.dorsal;
  document.getElementById('editar-position').value     = p.position;
  new bootstrap.Modal(document.getElementById('modalEditar')).show();
}

// ── Guardar edición ───────────────────────────────────────
async function guardarEdicion() {
  const body = {
    code:     document.getElementById('editar-code').value,
    name:     document.getElementById('editar-name').value.trim(),
    dorsal:   document.getElementById('editar-dorsal').value.trim(),
    position: document.getElementById('editar-position').value.trim()
  };
  const res  = await fetch(API, { method: 'PUT', headers: {'Content-Type':'application/json'}, body: JSON.stringify(body) });
  const data = await res.json();
  alert(data.message || data.error);
  if (res.ok) {
    bootstrap.Modal.getInstance(document.getElementById('modalEditar')).hide();
    cargarJugadores();
  }
}

// ── Borrar ────────────────────────────────────────────────
async function borrarJugador(code) {
  if (!confirm('¿Seguro que quieres borrar este jugador?')) return;
  const res  = await fetch(`${API}?code=${code}`, { method: 'DELETE' });
  const data = await res.json();
  alert(data.message || data.error);
  cargarJugadores();
}

// ── Ver ───────────────────────────────────────────────────
async function verJugador(code) {
  const res = await fetch(`${API}?code=${code}`);
  const p   = await res.json();
  document.getElementById('ver-code').value     = p.code;
  document.getElementById('ver-name').value     = p.name;
  document.getElementById('ver-dorsal').value   = p.dorsal;
  document.getElementById('ver-position').value = p.position;
  new bootstrap.Modal(document.getElementById('modalVer')).show();
}

cargarJugadores();
</script>
</body>
</html>