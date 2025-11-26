<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@480;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/layoutstyle.css">
    <title>Dashboard</title>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Logo -->
        <h1 class="logo">
            <img src="../img/Biteblisss.png" alt="BiteBliss Logo">
        </h1>
        <!-- List of menus -->
        <div class="sidebar-menus">
            <a href="customer_panel.php"><ion-icon name="storefront-outline"></ion-icon>Home</a>
            <!-- <a href="viewfooditem.php"><ion-icon name="pizza-outline"></ion-icon>Food Items</a> -->
            <a href="cart.php"><ion-icon name="cart-outline"></ion-icon>My Cart</a>
            <a href="vieworders.php"><ion-icon name="bag-handle-outline"></ion-icon> My Orders</a>
        </div>
        <!-- Logout -->
        <div class="sidebar-logout">
            <a href="../logout.php"><ion-icon name="log-out-outline"></ion-icon>Logout</a>
        </div>
    </div>
    <!-- main -->
    <div class="main">
        <!-- main navbar -->
        <div class="main-navbar">
            <!-- menu when appear on mobile version -->
            <ion-icon class="menu-toggle" name="menu-outline"></ion-icon>
            <!-- search bar -->
            <div class="search">
                <input type="text" name="searchs" id="searchs" placeholder="What you want to eat?">
                <button class="search-btn">Search</button>
            </div>
            <!-- profile icon on left side of navbar -->
            <div class="profile">
                <!-- <a href="#" class="cart"><ion-icon name="cart-outline"></ion-icon></a> -->
                <a href="profile.php" class="user"><ion-icon name="person-outline"></ion-icon></a>
            </div>
        </div>
            <hr class="divider">   
           
