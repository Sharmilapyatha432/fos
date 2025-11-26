<?php
/**
 * Delivery person header/navigation
 */
?>
<link rel="stylesheet" href="../css/adminpanel.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

<nav class="navbar">
    <div class="logo">
        <h1 class="logoo">BITEBLISS</h1>
    </div>
    <button class="nav-toggle" aria-label="Toggle menu">
        <i class="fa-solid fa-bars"></i>
    </button>
    <ul class="nav-links">
        <li><a href="c_orders.php"><i class="fas fa-home"></i><span>Home</span></a></li>
        <li><a href="c_orders.php"><i class="fa-solid fa-truck-ramp-box"></i><span>Current Order</span></a></li>
        <li><a href="d_order.php"><i class="fa-solid fa-clock-rotate-left"></i><span>Delivered Order</span></a></li>
        <li><a href="check_notifications.php"><i class="fa-solid fa-bell"></i><span>Notification</span></a></li>
        <li><a href="profile.php"><i class="fa-solid fa-circle-user"></i><span>Profile</span></a></li>
        <li><a href="../logout.php"><i class="fa-solid fa-arrow-right-from-bracket"></i><span>Logout</span></a></li>
    </ul>
</nav>
<script>
    const toggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');
    if (toggle && navLinks) {
        toggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });
    }
</script>
