<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title" style="padding-top:0; margin-top:0; color:#f00;">Ubah Admin</h3>
			</div>
			<hr/>
			<div class="box-body">	
				<?php
						
					if (isset($_POST['save'])) {
						$admin->ubah_admin($_POST['email'],$_POST['pass'],$_POST['nama'],$_FILES['gambar'],$_GET['id']);
						echo "<script>bootbox.alert('Data Terubah', function(){
							window.location = 'index.php?page=admin';
						});</script>";
					}
					$adm = $admin->ambil_admin($_GET['id']);
				?>	
				<form method="POST" id="forminput" enctype="multipart/form-data">
					<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" name="email" id="formemail" value="<?php echo $adm['email']; ?>" placeholder="Masukan Email">
					</div>
					<div class="form-group">
						<label>Password</label>
						<input type="password" class="form-control" name="pass" id="formpass" value="<?php echo $adm['password']; ?>" placeholder="Masukan Password">
					</div>
					<div class="form-group">
						<label>Nama</label>
						<input type="text" class="form-control" name="nama" id="formnama" value="<?php echo $adm['nama']; ?>" placeholder="Masukan Nama">
					</div>
					<div class="form-group">
						<label>Gambar</label>
						<img src="gambar_admin/<?php echo $adm['gambar']; ?>" width="50" class="img-responsive">
						
						<input type="file" class="form-control" name="gambar" id="formgambar">
					</div>
					<button id="formbtn" class="btn btn-primary" name="save"><i class="fa fa-save"></i> Simpan</button>
					<a href="index.php?page=admin" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Back to admin</a>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	//fungsi hide div
	$(function(){
		setTimeout(function(){$("#divAlert").fadeOut(900)}, 500);
	});
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
			if (!validateText('formemail')) {
				$('#formemail').focus();
				return false;
			}
			if (!validateText('formpass')) {
				$('#formpass').focus();
				return false;
			}
			if (!validateText('formnama')) {
				$('#formnama').focus();
				return false;
			}
			return true;
		});
	});
</script>