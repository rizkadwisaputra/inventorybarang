<?php 
	$kode = $penjualan->kode_otomatis();
	$subtotal = $penjualan->hitung_total_sementara($kode);
	$cekbarang = $penjualan->cek_data_barangp($kode);
	
	if (isset($_POST['tambah'])) {
		$cekitem = $penjualan->cek_item($_GET['proses'],$_POST['item']);
		if ($cekitem === true) {
			$penjualan->tambah_penjualan_sementara($kode,$_GET['proses'],$_POST['item']);
			echo "<script>location='index.php?page=tambahpenjualan';</script>";
		}
	}
	if (isset($_POST['save'])) {
		if ($_POST['totalbayar'] < $subtotal ) {
			echo "<script>bootbox.alert('Total Bayar Tidak Cukup!', function(){

			});</script>";
		}else{
			$penjualan->simpan_penjualan($_POST['kdpenjualan'],$_POST['tglpenjualan'],$_POST['totalbayar'],$subtotal);
			$pen = $penjualan->ambil_kdpen();
			$kodepen = $pen['kd_penjualan'];
			$kem = $_POST['totalbayar'] - $subtotal;
			$kembalian = number_format($kem);
			echo "<script>
				bootbox.confirm('Kembalian Rp. $kembalian, Lanjutkan Cetak Nota!', function(confirmed){
	        	if (confirmed) {
	        		window.location ='index.php?page=tambahpenjualan';
	        	  	window.open('nota/cetaknotapenjualan.php?kdpenjualan=$kodepen', '_blank');
	        	}else{
	        		window.location ='index.php?page=tambahpenjualan';
	        	}
	        });
			</script>";
		}
		
	}
	if (isset($_GET['hapus'])) {
		$penjualan->hapus_penjualan_sementara($_GET['hapus']);
		echo "<script>location='index.php?page=tambahpenjualan';</script>";
	}
	$kdbar = "";
	$namabr = "";
	if (isset($_GET['proses'])) {
		$bar = $barang->ambil_barang($_GET['proses']);
		$namabr = $bar['nama_barang'];
		$kdbar = $_GET['proses'];
	}
?>
<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				Barang
			</div>
			<div class="panel-body">
				<form method="POST">
					<div class="form-group">
						<label>Kd Barang</label>
						<input type="text" class="form-control" id="kdbarang" name="kdbarang" disabled="disabled" value="<?php echo $kdbar; ?>">
					</div>
					<div class="form-group">
						<label>Nama Barang</label>
						<input type="text" class="form-control" disabled="disabled" value="<?php echo $namabr; ?>">
					</div>
					<div class="form-group">
						<label>Jumlah Item</label>
						<input type="number" class="form-control" name="item" id="item" max="10000" min="0">
					</div>

			</div>
			<div class="panel-footer">
			<?php if ($kdbar === ""): ?>				
				<button class="btn btn-info" name="tambah" id="tambah" disabled="disabled"><i class="fa fa-plus"></i> Tambah</button>
			<?php endif ?>
			<?php if ($kdbar !== ""): ?>
				<button class="btn btn-info" name="tambah" id="tambah"><i class="fa fa-plus"></i> Tambah</button>
			<?php endif ?>
			</div>
				</form>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				Penjualan
			</div>
			<div class="panel-body">
				<!--Form-->
				<form method="POST">
					<div class="form-group">
						<label>Kode Penjualan</label>
						<input type="text" class="form-control" name="kdpenjualan" id="kdpenjualan" maxlength="8" readonly="true" value="<?php echo $kode; ?>">
					</div>
					<div class="form-group">
						<label>Tanggal Penjualan</label>
						<input type="date" class="form-control" name="tglpenjualan" id="tglpenjualan">
					</div>
					<div class="form-group">
						<label>Total Bayar</label>
						<input type="number" class="form-control" name="totalbayar" id="totalbayar">
					</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="panel-footer" align="center">
		<?php if ($cekbarang === true): ?>
			<button id="formbtn" class="btn btn-primary" name="save"><i class="fa fa-save"></i> Simpan</button>
		<?php endif ?>
		<?php if ($cekbarang === false): ?>
			<button id="formbtn" class="btn btn-primary" name="save" disabled="disabled"><i class="fa fa-save"></i> Simpan</button>
		<?php endif ?>
		</div>				
				</form><!--End Form-->
	</div>
	<div class="col-md-12">
		<table class="table table-bordered table-responsive table-hover">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Barang</th>
					<th>Satuan</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Total</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php  
					if ($cekbarang === false) {
						echo "<tr><td colspan='7' align='center'>Data saat ini kosong</td></tr>";
					}
					else{
					$br = $penjualan->tampil_barang_sementara($kode);
					foreach ($br as $index => $data) {
				?>
				<tr>
					<td><?php echo $index + 1; ?></td>
					<td><?php echo $data['nama_barang']; ?></td>
					<td><?php echo $data['satuan']; ?></td>
					<td><?php echo number_format($data['harga']); ?></td>
					<td><?php echo $data['item']; ?></td>
					<td><?php echo number_format($data['total']); ?></td>
					<td>
						<a href="index.php?page=tambahpenjualan&hapus=<?php echo $data['id_penjualan_sementara']; ?>" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Hapus</a>
					</td>
				</tr>
				<?php } }?>
				<tr class="active">
					<td colspan="5" align="center"><strong>Subtotal</strong></td>
					<td colspan="2"><?php echo number_format($subtotal); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!--data barangnya-->
<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Data Barang
            </div>
            <div class="panel-body">
                <div class="table">
                    <table class="table table-striped table-bordered table-hover" id="tabelku">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama</th>
                                <th>Satuan</th>
                                <th>Harga Jual</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                $brg = $penjualan->tampil_barang_penjualan();
                                foreach ($brg as $index => $data) {
                            ?>
                            <tr class="odd gradeX">
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $data['kd_barang']; ?></td>
                                <td><?php echo $data['nama_barang']; ?></td>
                                <td><?php echo $data['satuan']; ?></td>
                                <td><?php echo number_format($data['harga_jual']); ?></td>
                                <td>
                                    <a href="index.php?page=tambahpenjualan&proses=<?php echo $data['kd_barang']; ?>" class="btn btn-success btn-xs"><i class="fa fa-download"></i> Prosess</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>   
            </div>
        </div>
        <!--End Advanced Tables -->
    </div>
</div>
<?php  
	if (isset($_GET['proses'])) {
		echo "<script>
			$('#item').focus();
		</script>";
	}
?>
<script>
	//upper
	$(function(){
    	$('#satuan').focusout(function() {
        	// Uppercase-ize contents
        	this.value = this.value.toLocaleUpperCase();
    	});
	});
	//fungsi hide div
	$(function(){
		setTimeout(function(){$("#divAlert").fadeOut(900)}, 500);
	});
	//validasi form
	function validateText(id){
		if ($('#'+id).val()== null || $('#'+id).val()== "") {
			var div = $('#'+id).closest('div');
			div.addClass("has-error has-feedback");
			return false;
		}
		else{
			var div = $('#'+id).closest('div');
			div.removeClass("has-error has-feedback");
			return true;	
		}
	}
	$(document).ready(function(){
		$("#formbtn").click(function(){
			if (!validateText('tglpenjualan')) {
				$('#tglpenjualan').focus();
				return false;
			}
			else if (!validateText('totalbayar')) {
				$('#totalbayar').focus();
				return false;
			}
			return true;
		});
	});
	$(document).ready(function(){
		$("#tambah").click(function(){
			if (!validateText('item')) {
				$('#item').focus();
				return false;
			}
			return true;
		});
	});
</script>