<?php
// Periksa keberadaan parameter id sebelum diproses lebih lanjut
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config.php";
    
    // siapkan statement SELECT
    $sql = "SELECT * FROM employees WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Ikat variabel ke pernyataan yang disiapkan sebagai parameter
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Setel parameter
        $param_id = trim($_GET["id"]);
        
        // Mencoba mengeksekusi pernyataan yang telah disiapkan
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Ambil baris hasil sebagai array asosiatif. Karena kumpulan hasil hanya berisi satu baris, kita tidak perlu menggunakan while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // menarik record perorangan
                $name = $row["name"];
                $address = $row["address"];
                $salary = $row["salary"];
                $department = $row["department"];
                $positon = $row["position"];
                $allowance = $row["allowance"];
            } else{
                // URL tidak berisi parameter id yang valid. Alihkan ke error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Terjadi kesalahan. Silakan coba lagi.";
        }
    }
     
    // mengakhiri statemen
    mysqli_stmt_close($stmt);
    
    // mengakhiri config
    mysqli_close($link);
} else{
    // URL tidak berisi parameter ID. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title>Dashboard Admin</title>
    
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css" />
    <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css" />
    <!-- endinject -->
    <!-- plugin css for this page -->
    <link
      rel="stylesheet"
      href="vendors/datatables.net-bs4/dataTables.bootstrap4.css"
    />
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/style.css" />
    <!-- endinject -->
    <!-- <link rel="shortcut icon" href="images/favicon.png" /> -->
</head>
<body>
<div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="navbar-brand-wrapper d-flex justify-content-center">
          <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand brand-logo">PAS</a>
          <a class="navbar-brand brand-logo-mini" href="index.html">PAS</a>
            <button
              class="navbar-toggler navbar-toggler align-self-center"
              type="button"
              data-toggle="minimize"
            >
              <span class="mdi mdi-sort-variant"></span>
            </button>
          </div>
        </div>
        <div
          class="navbar-menu-wrapper d-flex align-items-center justify-content-end"
        >
          <ul class="navbar-nav mr-lg-4 w-100">
            <li class="nav-item nav-search d-none d-lg-block w-100">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="search">
                    <i class="mdi mdi-magnify"></i>
                  </span>
                </div>
                <input
                  type="text"
                  class="form-control"
                  placeholder="Search now"
                  aria-label="search"
                  aria-describedby="search"
                />
              </div>
            </li>
          </ul>
          <ul class="navbar-nav navbar-nav-right">
            
          </ul>
          <button
            class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
            type="button"
            data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item">
              <a class="nav-link" href="index.php">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">Dashboard</span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between flex-wrap">
                  <div class="d-flex align-items-end flex-wrap">
                    <div class="mr-md-3 mr-xl-5">
                      <h2>Detail Data Pekerja </h2>
                    </div>
                    <div class="d-flex">
                    </div>
                  </div>
                  <!-- <div
                    class="d-flex justify-content-between align-items-end flex-wrap">
                    <a href="create.php" class="btn btn-primary mt-2 mt-xl-0">Tambah Data</a>
                  </div> -->
                </div>
              </div>
            </div>
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                  <div class="form-group">
                     <p class="form-control-static"><b>Nama : </b><?php echo $row["name"]; ?></p>
                        </div>
                        <div class="form-group">
                            <p class="form-control-static"><b>Alamat : </b><?php echo $row["address"]; ?></p>
                        </div>
                        <div class="form-group">
                            <p class="form-control-static"><b>Gaji : </b><?php echo $row["salary"]; ?></p>
                        </div>
                        <div class="form-group">
                            <p class="form-control-static"><b>Department : </b><?php echo $row["department"]; ?></p>
                        </div>
                        <div class="form-group">
                            <p class="form-control-static"><b>Position : </b><?php echo $row["position"]; ?></p>
                        </div>
                        <div class="form-group">
                            <p class="form-control-static"><b>Tunjangan : </b><?php echo $row["allowance"]; ?></p>
                        </div>
                        <p>
                         <?php echo "<a href='update.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'><span class='btn btn-primary'>Update</span></a>"; ?>                     
                         <?php echo "<a href='delete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'><span class='btn btn-danger'>Delete</span></a>";?>
                       <a href="welcome.php" class="btn btn-primary">Kembali</a></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
          <!-- content-wrapper ends -->
          
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div
              class="d-sm-flex justify-content-center justify-content-sm-between"
            >
              <span
                class="text-muted d-block text-center text-sm-left d-sm-inline-block"
                >Copyright © bootstrapdash.com 2020</span
              >
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- plugins:js -->
    <script src="vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="js/dashboard.js"></script>
    <script src="js/data-table.js"></script>
    <script src="js/jquery.dataTables.js"></script>
    <script src="js/dataTables.bootstrap4.js"></script>
    <!-- End custom js for this page-->
    <script src="js/jquery.cookie.js" type="text/javascript"></script>
</body>
</html>