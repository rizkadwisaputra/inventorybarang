<div class="row">
  <div class="col-md-12">
    <h2>Inventory Barang</h2>   
    <h5>Welcome to Inventory Barang App. </h5>
  </div>
</div>
<hr />
<?php  
	$penjualan = $dashboard->penjualan_hariini();
	$pembelian = $dashboard->pembelian_hariini();
?>
<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-6">           
		<div class="panel panel-back noti-box">
            <span class="icon-box bg-color-green set-icon">
                <i class="fa fa-money"></i>
            </span>
           	<div class="text-box" >
                <p class="main-text"><?php echo $penjualan; ?> New</p>
                <p class="text-muted">Penjualan Hari Ini</p>
            </div>
        </div>
		</div>
	<div class="col-md-6 col-sm-6 col-xs-6">           
		<div class="panel panel-back noti-box">
            <span class="icon-box bg-color-red set-icon">
                <i class="fa fa-download"></i>
            </span>
        	<div class="text-box" >
                <p class="main-text"><?php echo $pembelian; ?> New</p>
                <p class="text-muted">Pembelian Hari Ini</p>
            </div>
        </div>
	</div>
</div>