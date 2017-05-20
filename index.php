<?php
  include 'class/class.php';
  error_reporting(0);
  if (empty($_SESSION['login_admin'])) {
    echo "<script>
    alert('Anda Belum Login!');
      window.location='login/index.php';
    </script>";

  }
  function tglIndonesia($str){
    $tr   = trim($str);
    $str    = str_replace(array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'), array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'), $tr);
    return $str;
  } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inventory Barang</title>
  <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!--FOR TABLE -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/bootbox.min.js"></script>
</head>
<body>
  <div id="wrapper">
    <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
          <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>    
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php">Inventory Admin</a> 
      </div>
      <div style="color: white;padding: 15px 20px 15px 20px;float: right;font-size: 16px;"> 
        <span style="margin-right:20px"><?php echo tglIndonesia(date('D, d F, Y')); ?></span>
        <a href="index.php?page=logout" class="btn btn-danger square-btn-adjust">Logout</a> 
      </div>
    </nav>   
<!-- /. NAV TOP  -->
    <nav class="navbar-default navbar-side" role="navigation">
      <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
          <li class="text-center">
            <img src="gambar_admin/<?php echo $_SESSION['login_admin']['gambar']; ?>" class="user-image img-circle img-responsive"/>
          </li>
          <li>
            <a  class="active-menu" href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a>
          </li> 
          <li>
            <a  href="#"><i class="fa fa-money"></i> Pembelian<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
              <li>
                <a  href="index.php?page=barangpembelian"><i class="fa fa-cube"></i> Data Barang Pembelian</a>
              </li>
              <li>
                <a  href="index.php?page=pembelian"><i class="fa fa-database"></i> Data Pembelian</a>
              </li>
              <li>
                <a  href="index.php?page=tambahpembelian"><i class="fa fa-plus-square"></i> Tambah Data</a>
              </li>
            </ul>
          </li>
          <li>
            <a  href="#"><i class="fa fa-money"></i> Penjualan<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
              <li>
                <a  href="index.php?page=penjualan"><i class="fa fa-database"></i> Data Penjualan</a>
              </li>
              <li>
                <a  href="index.php?page=tambahpenjualan"><i class="fa fa-plus-square"></i> Tambah Data</a>
              </li>
            </ul>
          </li>
          <li>
            <a  href="index.php?page=barang"><i class="fa fa-qrcode"></i> Barang</a>
          </li>
          <li>
            <a  href="index.php?page=supplier"><i class="fa fa-group"></i> Supplier</a>
          </li>
          <li>
            <a  href="#"><i class="fa fa-book"></i> Laporan<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
              <li>
                <a  href="index.php?page=laporanpenjualan"><i class="fa fa-file-archive-o"></i> Penjualan</a>
              </li>
              <li>
                <a  href="index.php?page=laporanpembelian"><i class="fa fa-file-archive-o"></i> Pembelian</a>
              </li>
              <li>
                <a  href="index.php?page=laporanprofit"><i class="fa fa-dollar"></i> Profit</a>
              </li>
            </ul>
          </li>
          <li>
            <a  href="#"><i class="fa fa-wrench"></i> Pengaturan<span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
              <li>
                <a  href="index.php?page=admin"><i class="fa fa-user"></i> Admin</a>
              </li>
              <li>
                <a  href="index.php?page=perusahaan"><i class="fa fa-home"></i> Perusahaan</a>
              </li>
            </ul>
          </li>
      </div>      
    </nav>  
    <!-- /. NAV SIDE  -->
    <div id="page-wrapper">
      <div id="page-inner">
        <?php  
          if (isset($_GET['page'])) {
            if ($_GET['page']=="admin") {
              include 'admin.php';
            }
            elseif ($_GET['page']=="tambahadmin") {
              include 'tambahadmin.php';
            }
            elseif ($_GET['page']=="ubahadmin") {
              include 'ubahadmin.php';
            }
            elseif ($_GET['page']=="barang") {
              include 'barang.php';
            }
            elseif ($_GET['page']=="tambahbarang") {
              include 'tambahbarang.php';
            }
            elseif ($_GET['page']=="ubahbarang") {
              include 'ubahbarang.php';
            }
            elseif ($_GET['page']=="supplier") {
              include 'supplier.php';
            }
            elseif ($_GET['page']=="tambahsupplier") {
              include 'tambahsupplier.php';
            }
            elseif ($_GET['page']=="ubahsupplier") {
              include 'ubahsupplier.php';
            }
            elseif ($_GET['page']=="pembelian") {
              include 'pembelian.php';
            }
            elseif ($_GET['page']=="tambahpembelian") {
              include 'tambahpembelian.php';
            }
            elseif ($_GET['page']=="barangpembelian") {
              include 'barangpembelian.php';
            }
            elseif ($_GET['page']=="simpanbaranggudang") {
              include 'simpanbaranggudang.php';
            }
            elseif ($_GET['page']=="penjualan") {
              include 'penjualan.php';
            }
            elseif ($_GET['page']=="tambahpenjualan") {
              include 'tambahpenjualan.php';
            }
            elseif ($_GET['page']=="laporanpenjualan") {
              include 'laporanpenjualan.php';
            }
            elseif ($_GET['page']=="laporanpembelian") {
              include 'laporanpembelian.php';
            }
            elseif ($_GET['page']=="laporanprofit") {
              include 'laporanprofit.php';
            }
            elseif ($_GET['page']=="perusahaan") {
              include 'perusahaan.php';
            }
            elseif ($_GET['page']=="logout") {
              session_destroy();
              echo "<script>location='login/';</script>";
            }
          }
          else{
            include 'dashboard.php';
          }
        ?>  
      </div>
    </div>
  </div>
     <!-- /. WRAPPER  -->
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!--DATA TABLE-->
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
      $(document).ready(function () {
        $('#tabelku').dataTable();
      });
    </script>
    <script>
    $(document).on("click", "#alertHapus", function(e){
      e.preventDefault();
      var link = $(this).attr("href");
      bootbox.confirm("Lanjutkan Menghapus!", function(confirmed){
        if (confirmed) {
          window.location.href = link;
        };
      });
    });
    </script>
    <script src="assets/js/custom.js"></script>
</body>
</html>