<div class="content-header">
  <div class="container-fluid">
    <h1 class="m-0">Players</h1>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Players list</h3>

        <button class="btn btn-primary btn-sm" onclick="abrirModalPlayer()">
          <i class="fas fa-plus"></i> Add Player
        </button>
      </div>

      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Dorsal</th>
              <th>Position</th>
              <th>Team</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody id="tbodyPlayers">
            <tr>
              <td colspan="6" class="text-center">Loading...</td>
            </tr>
          </tbody>

        </table>
      </div>
    </div>

  </div>
</section>


<!-- Modal Player -->
<div class="modal fade" id="modalPlayer" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Player</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <input type="hidden" id="player-id">

        <div class="form-group">
          <label>Name</label>
          <input type="text" id="player-name" class="form-control">
        </div>

        <div class="form-group">
          <label>Dorsal</label>
          <input type="number" id="player-dorsal" class="form-control">
        </div>

        <div class="form-group">
          <label>Position</label>
          <input type="text" id="player-position" class="form-control">
        </div>

        <div class="form-group">
          <label>Team</label>
          <select id="player-team" class="form-control">
            <option value="">Loading Team...</option>
          </select>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="guardarPlayer()">Save</button>
      </div>

    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

const API_PLAYERS = 'api/players.php';
const API_TEAMS   = 'api/teams.php';


function abrirModalPlayer() {
  limpiarModalPlayer();
  cargarEquipos();
  $('#modalPlayer').modal('show');
}


async function cargarEquipos() {

  const res  = await fetch(API_TEAMS);
  const data = await res.json();

  const select = document.getElementById('player-team');

  select.innerHTML = data.map(t =>
    `<option value="${t.id}">${t.name}</option>`
  ).join('');

}


async function cargarPlayers() {

  const res  = await fetch(API_PLAYERS);
  const data = await res.json();

  const tbody = document.getElementById('tbodyPlayers');

  if (!data.length) {
    tbody.innerHTML = `<tr>
      <td colspan="6" class="text-center">No Players</td>
    </tr>`;
    return;
  }

  tbody.innerHTML = data.map(p => `
    <tr>
      <td>${p.id}</td>
      <td>${p.name}</td>
      <td>${p.dorsal}</td>
      <td>${p.position}</td>
      <td>${p.team_name}</td>

      <td>
        <button class="btn btn-info btn-sm" onclick="editarPlayer(${p.id})">
          Upload
        </button>

        <button class="btn btn-danger btn-sm" onclick="borrarPlayer(${p.id})">
          Delete
        </button>
      </td>
    </tr>
  `).join('');
}


async function guardarPlayer() {

  const id = document.getElementById('player-id').value;

  const body = {
    id: id || undefined,
    name: document.getElementById('player-name').value.trim(),
    dorsal: document.getElementById('player-dorsal').value.trim(),
    position: document.getElementById('player-position').value.trim(),
    team_id: document.getElementById('player-team').value
  };

  const method = id ? 'PUT' : 'POST';

  const res = await fetch(API_PLAYERS, {
    method,
    headers: { 'Content-Type':'application/json' },
    body: JSON.stringify(body)
  });

  const data = await res.json();

  Swal.fire({
    icon: res.ok ? 'success' : 'error',
    title: res.ok ? 'Éxito' : 'Error',
    text: data.message || data.error
  });

  if (res.ok) {

    $('#modalPlayer').modal('hide');
    cargarPlayers();

  }

}


async function editarPlayer(id) {

  await cargarEquipos();

  const res = await fetch(`${API_PLAYERS}?id=${id}`);
  const p   = await res.json();

  document.getElementById('player-id').value = p.id;
  document.getElementById('player-name').value = p.name;
  document.getElementById('player-dorsal').value = p.dorsal;
  document.getElementById('player-position').value = p.position;
  document.getElementById('player-team').value = p.team_id;

  $('#modalPlayer').modal('show');

}


async function borrarPlayer(id) {

  const result = await Swal.fire({
    title: 'Delete player?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete'
  });

  if (!result.isConfirmed) return;

  const res  = await fetch(`${API_PLAYERS}?id=${id}`, { method:'DELETE' });
  const data = await res.json();

  Swal.fire({
    icon: res.ok ? 'success' : 'error',
    title: res.ok ? 'Eliminado' : 'Error',
    text: data.message || data.error
  });

  cargarPlayers();

}


function limpiarModalPlayer() {

  document.getElementById('player-id').value = '';
  document.getElementById('player-name').value = '';
  document.getElementById('player-dorsal').value = '';
  document.getElementById('player-position').value = '';

}


$(document).ready(function() {
  cargarPlayers();
});

</script>