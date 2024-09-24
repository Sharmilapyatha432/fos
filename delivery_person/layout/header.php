<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Person Dashboard</title>
</head>
<body>
    
    <div id = "header">
        <div id = "logo">
            <img src = "#" alt = "">
        </div>
        <div id = "banner">BiteBliss Delivery Person Dashboard</div>
        <div id = "profile">
            <a href="../logout.php">Logout</a>
        </div>
    </div>

    <div id = "main"> -->

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="d-flex">
        <nav class="sidebar bg-dark p-3">
            <h2 class="text-white">Delivery Dashboard</h2>
            <ul class="nav flex-column">
                <li class="nav-item mb-3">
                    <a href="#" class="nav-link text-white"><i class="fas fa-home me-2"></i> Dashboard</a>
                </li>
                <li class="nav-item mb-3">
                    <a href="#" class="nav-link text-white"><i class="fas fa-box me-2"></i> Current Orders</a>
                </li>
                <li class="nav-item mb-3">
                    <a href="#" class="nav-link text-white"><i class="fas fa-user me-2"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content w-100 p-4">
            <!-- Header -->
            <header class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="text-primary">Welcome, [Delivery Person Name]</h1>
                    <p class="lead text-muted">Track your deliveries and manage your profile.</p>
                </div>
            </header>

           
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
