<?php
// Check if the user is already logged in, if yes then redirect him to welcome page
// if (!isset($_SESSION["loggedin"]) && !$_SESSION["loggedin"] === true) {
//     header("location: welcome.php");
//     exit;
// }
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$name = $address = $salary = $department = $position = $allowance = "";
$name_err = $address_err = $salary_err = $department_err = $position_err = $allowance_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate name
    $input_name = trim($_POST["name"]);
    if (empty($input_name)) {
        $name_err = "Please enter a name.";
    } elseif (!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $name_err = "Please enter a valid name.";
    } else {
        $name = $input_name;
    }

    // Validate address address
    $input_address = trim($_POST["address"]);
    if (empty($input_address)) {
        $address_err = "Please enter an address.";
    } else {
        $address = $input_address;
    }

    // Validate salary
    $input_salary = trim($_POST["salary"]);
    if (empty($input_salary)) {
        $salary_err = "Please enter the salary amount.";
    } elseif (!ctype_digit($input_salary)) {
        $salary_err = "Please enter a positive integer value.";
    } else {
        $salary = $input_salary;
    }

    // validasi field department
    $input_department = trim($_POST["department"]);
    if (empty($input_department)) {
        $department_err = "Mohon masukkan sebuah department.";
    } else {
        $department = $input_department;
    }

    // validasi field position
    $input_position = trim($_POST["position"]);
    if (empty($input_position)) {
        $position_err = "Mohon masukkan sebuah position.";
    } else {
        $position = $input_position;
    }

    // validasi field allowance
    $input_allowance = trim($_POST["allowance"]);
    if (empty($input_allowance)) {
        $allowance_err = "Mohon masukkan sebuah allowance.";
    } else {
        $allowance = $input_allowance;
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($address_err) && empty($salary_err) && empty($department_err) && empty($position_err) && empty($allowance_err)) {
        // Prepare an update statement
        $sql = "UPDATE employees SET name=?, address=?, salary=?, department=?, position=?, allowance=? WHERE id=?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssi", $param_name, $param_address, $param_salary, $param_department, $param_position, $param_allowance, $param_id);

            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_salary = $salary;
            $param_department = $department;
            $param_position = $position;
            $param_allowance = $allowance;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: welcome.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);
        // Prepare a select statement
        $sql = "SELECT * FROM employees WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $name = $row["name"];
                    $address = $row["address"];
                    $salary = $row["salary"];
                    $department = $row["department"];
                    $position = $row["position"];
                    $allowance = $row["allowance"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<title>Update Data</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor1/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor1/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor1/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor1/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor1/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor1/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util1.css">
	<link rel="stylesheet" type="text/css" href="css/main1.css">
</head>
<body>
    <div class="container-contact100">
		<div class="wrap-contact100">
			<div class="contact100-form-title" style="background-image: url(images/bg-02.jpg);">
				<span class="contact100-form-title-1">
					Update Data Pekerja
				</span>

				<span class="contact100-form-title-2">
                Silakan edit nilai yang ada dan tekan submit untuk melakukan update.
				</span>
			</div>

			<form class="contact100-form validate-form" action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
				<div class="wrap-input100 validate-input <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>" data-validate="Name is required">
					<span class="label-input100">Nama:</span>
					<input class="input100" type="text" name="name" placeholder="Ubah Nama" value="<?php echo $name; ?>">
					<span class="focus-input100"><?php echo $name_err;?></span>
				</div>
                <div class="wrap-input100 validate-input <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>" data-validate = "Message is required">
					<span class="label-input100">Alamat:</span>
                    <input class="input100" type="text" name="address" placeholder="Enter email addess" value="<?php echo $address; ?>">
					<span class="focus-input100"><?php echo $address_err;?></span>
				</div>
				<div class="wrap-input100 validate-input  <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>" data-validate = "Valid email is required: ex@abc.xyz">
					<span class="label-input100">Gaji:</span>
					<input class="input100" type="text" name="salary" placeholder="Enter email addess" value="<?php echo $salary; ?>">
					<span class="focus-input100"><?php echo $salary_err;?></span>
				</div>

				<div class="wrap-input100 validate-input  <?php echo (!empty($department_err)) ? 'has-error' : ''; ?>" data-validate="department ">
					<span class="label-input100">Department:</span>
					<input class="input100" type="text" name="department" placeholder="Enter department"  value="<?php echo $department; ?>">
                    <span class="focus-input100"><?php echo $department_err;?></span>
                </div>
                <div class="wrap-input100 validate-input  <?php echo (!empty($position_err)) ? 'has-error' : ''; ?>" data-validate="Position">
					<span class="label-input100">Position:</span>
					<input class="input100" type="text" name="position" placeholder="Enter Position"  value="<?php echo $position; ?>">
                    <span class="focus-input100"><?php echo $position_err;?></span>
                </div>
                <div class="wrap-input100 validate-input  <?php echo (!empty($allowance_err)) ? 'has-error' : ''; ?>" data-validate="Tunjangan">
					<span class="label-input100">Tunjangan:</span>
					<input class="input100" type="text" name="allowance" placeholder="Enter Tunjangan"  value="<?php echo $allowance; ?>">
                    <span class="focus-input100"><?php echo $allowance_err;?></span>
                </div>

                <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                <input class="contact100-form-btn" type="submit" value="submit"/>
                <a class="contact100-form-btncancel" style="color:white; margin-right:50px" href="welcome.php">Cancel</a>
				<!-- <div class="container-contact100-form-btn">
					<button class="contact100-form-btn" >
						<span>
							Submit
							<i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
						</span>
                    </button>
                    <a class="contact100-form-btncancel" style="color:white; margin-left:10px" href="welcome.php">Cancel</a>
				</div> -->
			</form>
		</div>
	</div>

<!--===============================================================================================-->
	<script src="vendor1/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor1/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor1/bootstrap/js/popper.js"></script>
	<script src="vendor1/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor1/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor1/daterangepicker/moment.min.js"></script>
	<script src="vendor1/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor1/countdowntime/countdowntime.js"></script>

</body>
</html>