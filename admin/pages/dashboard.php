<?php 
$stats = new Dashboard();

?>
<h1 class="mt-5">Dashboard</h1>
<hr>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Accounts</h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $stats->total_accounts(); ?></h6>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Characters</h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $stats->total_characters(); ?></h6>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Tickets</h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $stats->total_tickets(); ?></h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Arena Teams</h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $stats->total_arena_teams(); ?></h6>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Guilds</h5>
                    <h6 class="card-subtitle mb-2 text-muted">1000</h6>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Arena Teams</h5>
                    <h6 class="card-subtitle mb-2 text-muted">1000</h6>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Arena Teams</h5>
                    <h6 class="card-subtitle mb-2 text-muted">1000</h6>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Arena Teams</h5>
                    <h6 class="card-subtitle mb-2 text-muted">1000</h6>

                </div>
            </div>
        </div>
    </div>
</div>