<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="d-flex">
        <nav class="sidebar bg-dark p-3">
            <h2 class="text-white">Customer Dashboard</h2>
            <ul class="nav flex-column">
                <li class="nav-item mb-3">
                    <a href="#" class="nav-link text-white"><i class="fa-solid fa-house"></i> Dashboard</a>
                </li>
                <li class="nav-item mb-3">
                    <a href="viewfooditem.php" class="nav-link text-white"><i class="fa-solid fa-bowl-food"></i> Food Items</a>
                </li>
                <li class="nav-item mb-3">
                    <a href="#" class="nav-link text-white"><i class="fa-solid fa-box"></i> My Orders</a>
                </li>
                <li class="nav-item mb-3">
                    <a href="#" class="nav-link text-white"><i class="fa-solid fa-user"></i> Profile</a>
                </li>
                <li class="nav-item">
                <a href="../logout.php" class="nav-link text-white"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>

                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content w-100 p-4">
            <!-- Header -->
            <header class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="text-primary">BiteBliss Customer Dashboard</h1>
                </div>
            </header>

               <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
