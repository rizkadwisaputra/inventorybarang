<?php  
    if (isset($_POST['save'])) {
        $barang->simpan_barang_gudang($_POST['kdbarang'],$_POST['hargaj'],$_GET['kdbl']);
        echo "<script>bootbox.alert('Data Berhasil Di Tambahkan!', function(){
				window.location = 'index.php?page=barangpembelian';
			});</script>";
    }
	$bel = $barang->ambil_barangpem($_GET['kdbl']);
?>
<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title" style="padding-top:0; margin-top:0; color:#f00;">Simpan Barang Ke Gudang</h3>
			</div>
			<hr/>
			<div class="box-body">	
				<form method="POST" id="forminput" enctype="multipart/form-data">
					<div class="form-group">
						<label>Kode Barang</label>
						<input type="text" style="text-transform:uppercase" class="form-control" name="kdbarang" id="kdbarang" placeholder="Masukan Kode Barang, Ex: BRG00001" maxlength="8">
						<div id="showresult" style="padding-top:4px; padding-bottom:0;"></div>
					</div>
					<div class="form-group">
						<label>Harga Beli</label>
						<input type="text" class="form-control" id="hargaj" readonly="true" value="<?php echo $bel['harga_beli']; ?>">
					</div>
					<div class="form-group">
						<label>Harga Jual</label>
						<input type="number" class="form-control" name="hargaj" id="hargaj" min="0">
					</div>
					<button id="formbtn" class="btn btn-primary" name="save"><i class="fa fa-save"></i> Simpan</button>
					<a href="index.php?page=barangpembelian" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Back to barang pembelian</a>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	//upper
	$(function(){
		$('#kdbarang').focusout(function() {
        	// Uppercase-ize contents
        	this.value = this.value.toLocaleUpperCase();
    	});
	});
	//fungsi hide div
	$(function(){
		setTimeout(function(){$("#divAlert").fadeOut(900)}, 500);
	});
	//ajax
	$(document).ready(function(){
		$('#kdbarang').blur(function(){
			var kdbarang = $(this).val();
			if (kdbarang == "") {
				$('#showresult').html("");
			}
			else{
				$.ajax({
					url: "validasi/cek-kdbarang.php?kdbarang="+kdbarang
				}).done(function(data){
					$('#showresult').html(data);
				});
			}
		});
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
	        var input = kdbarang.value;
			if (!validateText('kdbarang')) {
				$('#kdbarang').focus();
				return false;
			}
			else if (!(/^\S{0,}$/.test(input))) {
	           	$('#kdbarang').focus();
	           	bootbox.alert('Tidak Boleh Menggunakan Spasi');
	            return false;
	        }else if (!validateText('hargaj')) {
				$('#hargaj').focus();
				return false;
			}
			return true;
		});
	});
</script>
