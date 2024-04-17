<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
if(!isset($_SESSION['system'])){
    $system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
    foreach($system as $k => $v){
        $_SESSION['system'][$k] = $v;
    }
}
ob_end_flush();
?>
    <?php include('./header.php'); ?>
    
    <?php 
    if(isset($_SESSION['login_id']))
        header("location:index.php?page=home");
    ?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dormitory Management System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            width: 400px; /* Widened the card */
            border-radius: 20px; /* Rounded corners */
            background: linear-gradient(to right, #f12711, #f5af19); /* Gradient background */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); /* Soft shadow */
            padding: 40px;
            color: white; /* Text color */
        }

        .card-title {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-control {
            border: none;
            border-radius: 25px; /* Rounded corners */
            padding: 15px;
            color: #333;
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent white background */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Soft shadow */
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background on focus */
        }

        .btn-primary {
            background-color: #f5af19; /* Button color */
            border: none;
            border-radius: 25px; /* Rounded corners */
            padding: 15px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #f12711; /* Button color on hover */
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 25px; /* Rounded corners */
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <h2 class="card-title">Welcome Back!</h2>
            <form id="login-form">
                <div class="form-group">
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#login-form').submit(function(e){
            e.preventDefault()
            $('#login-form button[type="submit"]').attr('disabled',true).html('Logging in...');
            if($(this).find('.alert-danger').length > 0 )
                $(this).find('.alert-danger').remove();
            $.ajax({
                url:'ajax.php?action=login',
                method:'POST',
                data:$(this).serialize(),
                error:err=>{
                    console.log(err)
                    $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                },
                success:function(resp){
                    if(resp == 1){
                        location.href ='index.php?page=home';
                    }else{
                        $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
                        $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                    }
                }
            })
        })
    });
</script>
</body>
</html>