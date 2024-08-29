<?php



?>






<!-- HTML Code Starts From Here -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delievery Person Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <!-- Custome CSS Link Here-->
     <link rel="stylesheet" href="css/dp_reg.css">


</head>
<body>
    <!-- Form Layout -->
    <div class="container">
        <h3>Registration as Delievery Person</h3>
        <form class="row g-3" action="delievry_person_reg.php" method="post">
            <div class="col-md-6">
                <label for="firstname" class="form-label">First Name:</label>
                <input type="text" class="form-control" id="firstname" placeholder="Enter your first name" required>
            </div>
            <div class="col-md-6">
                <label for="lastname" class="form-label">Last Name:</label>
                <input type="text" class="form-control" id="lastname" placeholder="Enter your last name" required>
            </div>
            <div class="col-md-6">
                <label for="address" class="form-label">Address:</label>
                <input type="text" class="form-control" id="address" placeholder="Enter your address" required>
            </div>
            <div class="col-md-6">
                <label for="mobile" class="form-label">Mobile no:</label>
                <input type="tel" class="form-control" id="mobile" placeholder="Enter your mobile number" required>
            </div>
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Email:</label>
                <input type="email" class="form-control" id="inputEmail4" placeholder="Enter your email" required>
            </div>
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">Password:</label>
                <input type="password" class="form-control" id="inputPassword4" placeholder="Enter your password" required>
            </div>
            <div class="col-md-6">
                <label for="inputCity" class="form-label">Licenses Number:</label>
                <input type="text" class="form-control" id="inputCity" placeholder="Enter your licence number" required>
            </div>
            <div class="col-md-6">
                <label for="inputState" class="form-label">Vehicles:</label>
                <select id="inputState" class="form-select" required>
                    <option selected>Choose Vehicle </option>
                    <option>Motorcycle</option>
                    <option>Scooter</option>
                </select>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-success" align = "center">SINGUP</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>