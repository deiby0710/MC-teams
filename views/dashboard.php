<div class="content-header">
  <div class="container-fluid">
    <h1 class="m-0">Dashboard</h1>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="row">

      <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3 id="totalPlayers">0</h3>
            <p>Players</p>
          </div>
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3 id="totalTeams">0</h3>
            <p>Teams</p>
          </div>
          <div class="icon">
            <i class="fas fa-flag"></i>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3 id="avgPlayers">0</h3>
            <p>Players / Team</p>
          </div>
          <div class="icon">
            <i class="fas fa-chart-bar"></i>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

  async function cargarDashboard() {

    const players = await fetch('api/players.php').then(r => r.json());
    const teams   = await fetch('api/teams.php').then(r => r.json());

    document.getElementById('totalPlayers').innerText = players.length;
    document.getElementById('totalTeams').innerText = teams.length;

    const avg = teams.length ? (players.length / teams.length).toFixed(1) : 0;

    document.getElementById('avgPlayers').innerText = avg;

  }

  $(document).ready(function(){
    cargarDashboard();
  });

</script>