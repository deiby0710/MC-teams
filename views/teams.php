<div class="content-header">
  <div class="container-fluid">
    <h1 class="m-0">Teams</h1>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">List of teams</h3>
        <button class="btn btn-primary btn-sm" onclick="abrirModalCrear()">
            <i class="fas fa-plus"></i> Add team
        </button>
      </div>

      <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>League</th>
              <th>City</th>
              <th>Year Founded</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="tbodyTeams">
            <tr><td colspan="6" class="text-center">Loading...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>


<!-- Modal Crear / Editar -->
<div class="modal fade" id="modalTeam" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">Team</h4>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="team-id">

        <div class="form-group">
          <label>Name</label>
          <input type="text" id="team-name" class="form-control">
        </div>

        <div class="form-group">
          <label>League</label>
          <input type="text" id="team-league" class="form-control">
        </div>

        <div class="form-group">
          <label>City</label>
          <input type="text" id="team-city" class="form-control">
        </div>

        <div class="form-group">
          <label>Year Funded</label>
          <input type="number" id="team-founded" class="form-control">
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="guardarTeam()">Save</button>
      </div>

    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const API_TEAMS = 'api/teams.php';

  function abrirModalCrear() {
    limpiarModal();
    $('#modalTeam').modal('show');
  }

  async function cargarTeams() {
    const res = await fetch(API_TEAMS);
    const data = await res.json();
    const tbody = document.getElementById('tbodyTeams');

    if (!data.length) {
      tbody.innerHTML = '<tr><td colspan="6" class="text-center">Sin equipos</td></tr>';
      return;
    }

    tbody.innerHTML = data.map(t => `
      <tr>
        <td>${t.id}</td>
        <td>${t.name}</td>
        <td>${t.league}</td>
        <td>${t.city}</td>
        <td>${t.founded_year}</td>
        <td>
          <button class="btn btn-info btn-sm" onclick="editarTeam(${t.id})">
            Update
          </button>
          <button class="btn btn-danger btn-sm" onclick="borrarTeam(${t.id})">
            Delete
          </button>
        </td>
      </tr>
    `).join('');
  }

  async function guardarTeam() {
    const id = document.getElementById('team-id').value;

    const body = {
      id: id || undefined,
      name: document.getElementById('team-name').value.trim(),
      league: document.getElementById('team-league').value.trim(),
      city: document.getElementById('team-city').value.trim(),
      founded_year: document.getElementById('team-founded').value.trim()
    };

    const method = id ? 'PUT' : 'POST';

    const res = await fetch(API_TEAMS, {
      method,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });

    const data = await res.json();
    Swal.fire({
      icon: res.ok ? 'success' : 'error',
      title: res.ok ? 'Éxito' : 'Error',
      text: data.message || data.error
    });

    if (res.ok) {
      $('#modalTeam').modal('hide');
      limpiarModal();
      cargarTeams();
    }
  }

  async function editarTeam(id) {
    const res = await fetch(`${API_TEAMS}?id=${id}`);
    const t = await res.json();

    document.getElementById('team-id').value = t.id;
    document.getElementById('team-name').value = t.name;
    document.getElementById('team-league').value = t.league;
    document.getElementById('team-city').value = t.city;
    document.getElementById('team-founded').value = t.founded_year;

    $('#modalTeam').modal('show');
  }

  async function borrarTeam(id) {
    const result = await Swal.fire({
      title: '¿Eliminar equipo?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) return;

    const res  = await fetch(`${API_TEAMS}?id=${id}`, { method: 'DELETE' });
    const data = await res.json();

    Swal.fire({
      icon: res.ok ? 'success' : 'error',
      title: res.ok ? 'Eliminado' : 'Error',
      text: data.message || data.error
    });

    cargarTeams();
  }

  function limpiarModal() {
    document.getElementById('team-id').value = '';
    document.getElementById('team-name').value = '';
    document.getElementById('team-league').value = '';
    document.getElementById('team-city').value = '';
    document.getElementById('team-founded').value = '';
  }

  cargarTeams();
</script>