<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="container">

    <!-- Header Card -->
    <div class="header-card d-flex">
        <h2>Admin Dashboard</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- Dashboard Content Card -->
    <div class="dashboard-content-card">
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center">
                    <h5>Total Parcels</h5>
                    <h3>12</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <h5>Pending</h5>
                    <h3>11</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <h5>In Transit</h5>
                    <h3>16</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <h5>Delivered</h5>
                    <h3>19</h3>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <a href="send_parcel.php" class="btn btn-success">Send New Parcel</a>
            <a href="update_status.php" class="btn btn-info">Update Parcel Status</a>
            <a href="search_parcel.php" class="btn btn-primary">Search Parcel</a>
        </div>
    </div>

</div>

</body>
</html>
