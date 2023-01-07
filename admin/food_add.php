<?php

include('../config/db_connect.php');
session_start();
$chef_id = $_SESSION['chef_id'];
$error = ['food_name' => '', 'food_desc' => '', 'food_price' => '', 'food_img' => ''];

if (isset($_POST['submit'])) {
    $food_name = mysqli_real_escape_string($conn, $_POST['food_name']);
    $food_desc = mysqli_real_escape_string($conn, $_POST['food_desc']);
    $food_price = mysqli_real_escape_string($conn, $_POST['food_price']);
    $food_img = $_FILES["food_img"]["name"];
    $tmp_name = $_FILES['food_img']['tmp_name'];
    $folder = "images/" . $food_img;
    if ($_FILES["food_img"]["size"] > 1000000) {
        echo "Sorry, your file is too large.";
        $error['food_img'] = 'File size is too large(maximum limit is 1MB)';
    }
    if (array_filter($error)) {
        echo "Error found";
    } else {
        $img_ex = pathinfo($food_img, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);

        $allowed_exs = array("jpg", "jpeg", "png");

        if (in_array($img_ex_lc, $allowed_exs)) {
            $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
            $img_upload_path = 'uploads/' . $new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path);
            $sql_loc = "SELECT DISTINCT(chef_city) FROM chef where chef_id='$chef_id'";
            $result = mysqli_query($conn, $sql_loc);
            $food_city = mysqli_fetch_row($result)[0];
            $sql = "INSERT INTO food (chef_id,food_name,food_desc,food_price,food_img,food_city) VALUES ('$chef_id','$food_name','$food_desc','$food_price','$new_img_name','$food_city')";
            if (mysqli_query($conn, $sql)) {
                echo "done";
                header('Location: dashboard.php');
            } else {
                echo mysqli_error($conn);
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuarantineMeals</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <link rel="stylesheet" href="../style.css">
    <style>
        .suffix:hover {
            cursor: pointer;
        }

        .input-field label {
            color: orange;
        }

        /* label focus color */
        .input-field input:focus+label {
            color: orange !important;
        }

        /* textarea underline focus color */
        .input-field textarea:focus {
            border-bottom: 1px solid orange !important;
            box-shadow: 0 1px 0 0 orange !important;
        }

        /* textarea focus color */
        .input-field textarea:focus+label {
            color: orange !important;
        }

        /* label underline focus color */
        .input-field input:focus {
            border-bottom: 1px solid orange !important;
            box-shadow: 0 1px 0 0 orange !important;
        }

        /* icon prefix focus color */
        .input-field .prefix.active {
            color: orange !important;
        }
    </style>
</head>

<body>
    <div class="section">
        <div class="center">
            <h4>Tell us about your recipie!</h4>
        </div>
        <form action="food_add.php" class="container section" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col s12 m6 input-field">
                    <label for="food_name" class="active">Food name</label>
                    <input type="text" id="food_name" name="food_name" required>
                </div>
                <div class="col s12 m6 input-field">
                    <textarea class="materialize-textarea" id="food_desc" name="food_desc" required></textarea>
                    <label for="food_desc" class="active">Food description</label>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m6 input-field">
                    <i class="fas fa-rupee-sign prefix"></i>
                    <label for="food_price" class="active">Food Price</label>
                    <input type="number" id="food_price" name="food_price" required>
                </div>
                <div class="col s12 m6 input-field">
                    <label for="food_img" style="margin-top:15px;">Food image</label>
                    <input type="file" name="food_img" required>
                </div>
            </div>
            <div class="center">
                <div class="input-field">
                    <input type="submit" class="btn orange" name="submit">
                </div>
            </div>

        </form>
    </div>


</body>

</html>