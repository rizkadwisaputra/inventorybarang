<?php  
	session_start();
	//set jam
	date_default_timezone_set('Asia/Jakarta');
	class DataBase{
		private $host = "localhost";
		private $user = "root";
		private $pass = "";
		private $db = "inventory_barang";

		public function sambungkan(){
			mysql_connect($this->host,$this->user,$this->pass);
			mysql_select_db($this->db);
		}
	}
	//membuat class admin
	class Admin{
		//method insert data admin
		public function simpan_admin($email,$pass,$nama,$gambar){
			$namafile = $gambar['name'];
			//lokasi sementara
			$lokasifile = $gambar['tmp_name'];
			//upload
			move_uploaded_file($lokasifile, "gambar_admin/$namafile");
			//insert
			mysql_query("INSERT INTO admin(email,password,nama,gambar) VALUES('$email','$pass','$nama','$namafile')");
		}
		public function tampil_admin(){
			$qry = mysql_query("SELECT * FROM admin");
			while ($pecah = mysql_fetch_array($qry)) {
				//array
				$data[] = $pecah;
			}
			return $data;
		}
		public function ambil_admin($id){
			$qry = mysql_query("SELECT * FROM admin WHERE kd_admin= '$id'");
			$pecah = mysql_fetch_assoc($qry);
			return $pecah;
		}
		public function ubah_admin($email,$pass,$nama,$gambar,$id){
			$namafile = $gambar['name'];
			$lokasifile = $gambar['tmp_name'];
			//mengambil nama gambar sebelumnya untuk di hapus, akan di hapus
			//jika form gambar tidak kosong
			$ambil = $this->ambil_admin($id);
			$gambarhapus = $ambil['gambar'];
			if (!empty($lokasifile)) {
				//hapus gambar sebelumnya
				unlink("gambar_admin/$gambarhapus");
				//upload gambar baru
				move_uploaded_file($lokasifile, "gambar_admin/$namafile");
				//update
				mysql_query("UPDATE admin 
					SET email = '$email', password='$pass', nama='$nama', gambar='$namafile' WHERE kd_admin='$id'");
			}
			else{
				//update tanpa upload gambar
				mysql_query("UPDATE admin 
					SET email = '$email', password='$pass', nama='$nama' WHERE kd_admin='$id'");
			}
		}
		public function hapus_admin($hapus){
			//ambil nama gambar yang akan di hapus pada folder gambar
			$gbr = $this->ambil_admin($hapus);
			$namagbr = $gbr['gambar'];
			//hapus
			unlink("gambar_admin/$namagbr");
			mysql_query("DELETE FROM admin WHERE kd_admin= '$hapus'");
		}
		public function login_admin($email,$pass){
			// mencocokan data di db dengan username dan pass yang di inputkan
			$cek = mysql_query("SELECT * FROM admin WHERE email='$email' AND password='$pass'");
			//mengambil data orang yang login dan cocok
			$data = mysql_fetch_assoc($cek);
			// hitung data yang cocok
			$cocokan = mysql_num_rows($cek);
			//jika akun yang cocok lebih besar dari 0 maka bisa login
			if ($cocokan > 0) {
				//bisa login
				$_SESSION['login_admin']['id'] = $data['kd_admin'];
				$_SESSION['login_admin']['email'] = $data['email'];
				$_SESSION['login_admin']['nama'] = $data['nama'];
				$_SESSION['login_admin']['gambar'] = $data['gambar'];

				return true;
			}// selain itu (akun yang cocok tdk lebih dari 0) maka ggl
			else{
				return false;
			}
		}
	}
	class Barang{
		public function tampil_barang(){
			$qry = mysql_query("SELECT * FROM barang ORDER BY nama_barang ASC");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[] = $pecah;
			}
			return $data;
		}
		public function simpan_barang($kdbarang,$nama,$satuan,$hargaj,$hargab,$stok){
			mysql_query("INSERT INTO barang(kd_barang,nama_barang,satuan,harga_jual,harga_beli,stok) 
				VALUES('$kdbarang','$nama','$satuan','$hargaj','$hargab','$stok')");
		}
		public function ubah_barang($nama,$satuan,$hargaj,$hargab,$stok,$kd){
			mysql_query("UPDATE barang SET nama_barang='$nama', satuan='$satuan', harga_jual='$hargaj',harga_beli='$hargab',stok='$stok' WHERE kd_barang = '$kd' ");
		}
		public function ambil_barang($id){
			$qry = mysql_query("SELECT * FROM barang WHERE kd_barang = '$id'");
			$pecah = mysql_fetch_assoc($qry);

			return $pecah;
		}
		public function hapus_barang($kd){
			mysql_query("DELETE FROM barang WHERE kd_barang = '$kd'");
		}
		public function simpan_barang_gudang($kdbarang,$hargaj,$kdbl){
			$dat = $this->ambil_barangpem($kdbl);
			$nama = $dat['nama_barang_beli'];
			$satuan = $dat['satuan'];
			$hargab = $dat['harga_beli'];
			$stok = $dat['item'];
			mysql_query("INSERT INTO barang(kd_barang,nama_barang,satuan,harga_jual,harga_beli,stok) 
				VALUES('$kdbarang','$nama','$satuan','$hargaj','$hargab','$stok')");
			//update data barang pembelian dengan setatus 1
			mysql_query("UPDATE barang_pembelian SET status='1' WHERE kd_barang_beli ='$kdbl'");
		}
		public function ambil_barangpem($kd){
			$qry = mysql_query("SELECT * FROM barang_pembelian WHERE kd_barang_beli = '$kd'");
			$pecah = mysql_fetch_assoc($qry);
			return $pecah;
		}
	}
	class Supplier{
		public function tampil_supplier(){
			$qry = mysql_query("SELECT * FROM supplier");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[] = $pecah;
			}
			return $data;
		}
		public function simpan_supplier($nama,$alamat){
			mysql_query("INSERT INTO supplier(nama_supplier,alamat) VALUES('$nama','$alamat')");
		}
		public function ubah_supplier($nama,$alamat,$id){
			mysql_query("UPDATE supplier SET nama_supplier='$nama', alamat='$alamat' WHERE kd_supplier = '$id'");
		}
		public function hapus_supplier($id){
			mysql_query("DELETE FROM supplier WHERE kd_supplier= '$id'");
		}
		public function ambil_supplier($id){
			$qry = mysql_query("SELECT * FROM supplier WHERE kd_supplier= '$id'");
			$pecah = mysql_fetch_assoc($qry);
			return $pecah;
		}
	}
	class Pembelian{
		public function kode_otomatis(){
			$qry = mysql_query("SELECT MAX(kd_pembelian) AS kode FROM pembelian");
			$pecah = mysql_fetch_array($qry);
			$kode = substr($pecah['kode'], 3,5);
			$jum = $kode + 1;
			if ($jum < 10) {
				$id = "PEM0000".$jum;
			}
			else if($jum >= 10 && $jum < 100){
				$id = "PEM000".$jum;
			}
			else if($jum >= 100 && $jum < 1000){
				$id = "PEM00".$jum;
			}
			else{
				$id = "PEM0".$jum;
			}
			return $id;
		}
		public function tampil_pembelian(){
			$qry = mysql_query("SELECT * FROM pembelian p JOIN supplier s ON p.kd_supplier=s.kd_supplier ORDER BY kd_pembelian DESC");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$cek = mysql_num_rows($qry);
			if ($cek > 0) {
				return $data;
			}else{
				error_reporting(0);
			}
		}
		public function hitung_item_pembelian($kdpembelian){
			$qry = mysql_query("SELECT count(*) as jumlah FROM d_pembelian WHERE kd_pembelian = '$kdpembelian'");
			$pecah = mysql_fetch_array($qry);

			return $pecah;
		}
		//sementara
		public function tambah_barang_sementara($kode,$nama,$satuan,$hargab,$item){
			$tot = $item * $hargab;
			mysql_query("INSERT INTO barangp_sementara(kd_pembelian,nama_barangp, satuan,harga_barangp,item,total) 
				VALUES('$kode','$nama','$satuan','$hargab','$item','$tot')");
		}
		public function tampil_barang_sementara($kode){
			$qry = mysql_query("SELECT * FROM barangp_sementara WHERE kd_pembelian = '$kode'");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}
		public function hitung_total_sementara($kode){
			$qry = mysql_query("SELECT sum(total) as jumlah FROM barangp_sementara WHERE kd_pembelian = '$kode'");
			$pecah = mysql_fetch_array($qry);
			$cek = $this->cek_data_barangp($kode);
			if ($cek === true) {
				$subtotal = $pecah['jumlah'];
			}
			else{
				$subtotal = 0;
			}
			return $subtotal;
		}
		public function hapus_barang_sementara($hapus){
			mysql_query("DELETE FROM barangp_sementara WHERE id_barangp ='$hapus'");
		}
		public function cek_data_barangp($kode){
			$qry = mysql_query("SELECT * FROM barangp_sementara WHERE kd_pembelian = '$kode'");
			$hitung = mysql_num_rows($qry);
			if ($hitung >=1) {
				return true;
			}
			else{
				return false;
			}
		}
		//end sementara
		public function simpan_pembelian($kdpembelian,$tglpembelian,$supplier,$totalpem){
			//insert pembelian
			$kdadmin = $_SESSION['login_admin']['id'];
			mysql_query("INSERT INTO pembelian(kd_pembelian,tgl_pembelian,kd_admin,kd_supplier,total_pembelian) 
				VALUES('$kdpembelian','$tglpembelian','$kdadmin','$supplier','$totalpem')");
			
			//insert data barang
			mysql_query("INSERT INTO barang_pembelian(kd_pembelian,nama_barang_beli,satuan,harga_beli,item,total) 
				SELECT kd_pembelian,nama_barangp,satuan,harga_barangp,item,total FROM barangp_sementara");
			//insert detail pembelian
			mysql_query("INSERT INTO d_pembelian(kd_pembelian,kd_barang_beli,jumlah,subtotal) 
				SELECT kd_pembelian, kd_barang_beli,item,total FROM barang_pembelian WHERE kd_pembelian='$kdpembelian'");
			//hapus data barang pembelian sementara
			mysql_query("DELETE FROM barangp_sementara WHERE kd_pembelian='$kdpembelian'");
		}
		public function tampil_barang_pembelian(){
			$qry = mysql_query("SELECT * FROM barang_pembelian WHERE status = '0'");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}	
		}
		public function ambil_kdpem(){
			$qry = mysql_query("SELECT * FROM pembelian ORDER BY kd_pembelian DESC LIMIT 1");
			$pecah = mysql_fetch_assoc($qry);
			return $pecah;
		}
		public function cek_hapuspembelian($kd){
			$qry = mysql_query("SELECT * FROM barang_pembelian WHERE kd_pembelian = '$kd' AND status ='0'");
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return false;
			}
			else{
				return true;
			}
		}
		public function hitung_jumlah_pembelian($kd){
			$qry = mysql_query("SELECT SUM(subtotal) as total FROM d_pembelian WHERE kd_pembelian = '$kd'");
			$pecah = mysql_fetch_assoc($qry);
			return $pecah['total'];
		}
		public function hapus_pembelian($kdpembelian){
			mysql_query("DELETE FROM pembelian WHERE kd_pembelian='$kdpembelian'");
			mysql_query("DELETE FROM barang_pembelian WHERE kd_pembelian = '$kdpembelian' AND status='1'");
		}
	}
	class Penjualan extends Barang {
		public function kode_otomatis(){
			$qry = mysql_query("SELECT MAX(kd_penjualan) AS kode FROM penjualan");
			$pecah = mysql_fetch_array($qry);
			$kode = substr($pecah['kode'], 3,5);
			$jum = $kode + 1;
			if ($jum < 10) {
				$id = "PEN0000".$jum;
			}
			else if($jum >= 10 && $jum < 100){
				$id = "PEN000".$jum;
			}
			else if($jum >= 100 && $jum < 1000){
				$id = "PEN00".$jum;
			}
			else{
				$id = "PEN0".$jum;
			}
			return $id;
		}
		public function tampil_barang_penjualan(){
			$qry = mysql_query("SELECT * FROM barang WHERE stok > 0 ORDER BY nama_barang ASC");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[] = $pecah;
			}
			return $data;
		}
		public function tampil_penjualan(){
			$qry = mysql_query("SELECT * FROM penjualan ORDER BY kd_penjualan DESC");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}
		public function cek_data_barangp($kode){
			$qry = mysql_query("SELECT * FROM penjualan_sementara WHERE kd_penjualan = '$kode'");
			$hitung = mysql_num_rows($qry);
			if ($hitung >=1) {
				return true;
			}
			else{
				return false;
			}
		}
		public function tampil_barang_sementara($kode){
			$qry = mysql_query("SELECT * FROM penjualan_sementara WHERE kd_penjualan = '$kode'");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}
		public function tambah_penjualan_sementara($kdpen, $kdbarang, $item){
			$bar = $this->ambil_barang($kdbarang);
			$namabr = $bar['nama_barang'];
			$satuan = $bar['satuan'];
			$harga = $bar['harga_jual'];
			$total = $harga * $item;
			mysql_query("INSERT INTO penjualan_sementara(kd_penjualan, kd_barang, nama_barang, satuan, harga, item, total) 
				VALUES('$kdpen', '$kdbarang','$namabr','$satuan','$harga','$item','$total')");
			// update barang
			$kurang = $bar['stok'] - $item;
			mysql_query("UPDATE barang SET stok = '$kurang' WHERE kd_barang = '$kdbarang'");
		}
		public function cek_item($kdbarang,$item){
			$data = $this->ambil_barang($kdbarang);
			$jumitem = $data['stok'];
			if ($item < $jumitem+1) {
				return true;
			}
			else{
				echo "<script>bootbox.alert('Item tidak cukup, $jumitem tersisa di gudang!', function(){
					window.location='index.php?page=tambahpenjualan';
				});</script>";
			}
		}
		public function hitung_total_sementara($kode){
			$qry = mysql_query("SELECT sum(total) as jumlah FROM penjualan_sementara WHERE kd_penjualan = '$kode'");
			$pecah = mysql_fetch_array($qry);
			$cek = $this->cek_data_barangp($kode);
			if ($cek === true) {
				$subtotal = $pecah['jumlah'];
			}
			else{
				$subtotal = 0;
			}
			return $subtotal;
		}
		public function hitung_item_penjualan($kdpenjualan){
			$qry = mysql_query("SELECT count(*) as jumlah FROM d_penjualan WHERE kd_penjualan = '$kdpenjualan'");
			$pecah = mysql_fetch_array($qry);

			return $pecah;
		}
		public function simpan_penjualan($kdpenjualan,$tglpen,$ttlbayar,$subtotal){
			//insert penjualan
			$kdadmin = $_SESSION['login_admin']['id'];
			mysql_query("INSERT INTO penjualan(kd_penjualan,tgl_penjualan,kd_admin,dibayar,total_penjualan) 
				VALUES('$kdpenjualan','$tglpen','$kdadmin','$ttlbayar','$subtotal')");
			
			//insert d penjualan
			mysql_query("INSERT INTO d_penjualan(kd_penjualan,kd_barang,jumlah,subtotal) 
				SELECT kd_penjualan, kd_barang,item,total FROM penjualan_sementara WHERE kd_penjualan='$kdpenjualan'");
			//hapus semua penjualan sementera
			mysql_query("DELETE FROM penjualan_sementara WHERE kd_penjualan = '$kdpenjualan'");
		}
		public function ambil_kdpen(){
			$qry = mysql_query("SELECT * FROM penjualan ORDER BY kd_penjualan DESC LIMIT 1");
			$pecah = mysql_fetch_assoc($qry);
			return $pecah;
		}
		public function hapus_penjualan_sementara($kd){
			//update barang, di kembalikan ke setok semula
			$datpen = $this->ambil_penjualan_sementara($kd);
			$datbar = $this->ambil_barang($datpen['kd_barang']);
			$stok = $datbar['stok'] + $datpen['item'];
			$kdbar = $datpen['kd_barang'];
			mysql_query("UPDATE barang SET stok ='$stok' WHERE kd_barang = '$kdbar'");
			//hapus
			mysql_query("DELETE FROM penjualan_sementara WHERE id_penjualan_sementara = '$kd'");
		}
		public function ambil_penjualan_sementara($kd){
			$qry = mysql_query("SELECT * FROM penjualan_sementara WHERE id_penjualan_sementara = '$kd'");
			$pecah = mysql_fetch_assoc($qry);
			return $pecah;
		}
	}
	class Nota{
		public function tampil_nota_pembelian($kd){
			$qry = mysql_query("SELECT * FROM supplier sup 
				JOIN pembelian pem ON pem.kd_supplier = sup.kd_supplier
				JOIN admin adm ON adm.kd_admin = pem.kd_admin
				JOIN d_pembelian dpem ON pem.kd_pembelian = dpem.kd_pembelian
				JOIN barang_pembelian bpem ON dpem.kd_barang_beli = bpem.kd_barang_beli
				WHERE pem.kd_pembelian = '$kd'");
			
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}	
		}
		public function ambil_nota_pembelian($kd){
			$qry = mysql_query("SELECT * FROM supplier sup 
				JOIN pembelian pem ON pem.kd_supplier = sup.kd_supplier
				JOIN admin adm ON adm.kd_admin = pem.kd_admin
				JOIN d_pembelian dpem ON pem.kd_pembelian = dpem.kd_pembelian
				JOIN barang_pembelian bpem ON dpem.kd_pembelian = bpem.kd_pembelian
				WHERE pem.kd_pembelian = '$kd'");
			$pecah = mysql_fetch_assoc($qry);
			return $pecah;
		}
		public function tampil_nota_penjualan($kd){
			$qry = mysql_query("SELECT * FROM penjualan pen
				JOIN admin adm ON adm.kd_admin = pen.kd_admin
				JOIN d_penjualan dpen ON pen.kd_penjualan = dpen.kd_penjualan
				JOIN barang bar ON dpen.kd_barang = bar.kd_barang
				WHERE pen.kd_penjualan = '$kd'");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}	
		}
		public function ambil_nota_penjualan($kd){
			$qry = mysql_query("SELECT * FROM penjualan pen
				JOIN admin adm ON adm.kd_admin = pen.kd_admin
				JOIN d_penjualan dpen ON pen.kd_penjualan = dpen.kd_penjualan
				JOIN barang bar ON dpen.kd_barang = bar.kd_barang
				WHERE pen.kd_penjualan = '$kd'");
			$pecah = mysql_fetch_assoc($qry);
			return $pecah;
		}
	}
	class Laporan{
		public function tampil_penjualan_bulan($bln1,$bln2){
			$qry = mysql_query("SELECT * FROM penjualan pen
				JOIN d_penjualan dpen ON pen.kd_penjualan = dpen.kd_penjualan
				JOIN barang bar ON dpen.kd_barang = bar.kd_barang 
				WHERE pen.tgl_penjualan BETWEEN '$bln1' AND '$bln2'");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}
		public function cek_penjualan_bulan($bln1,$bln2){
			$qry = mysql_query("SELECT * FROM penjualan pen
				JOIN d_penjualan dpen ON pen.kd_penjualan = dpen.kd_penjualan
				JOIN barang bar ON dpen.kd_barang = bar.kd_barang
				WHERE pen.tgl_penjualan BETWEEN '$bln1' AND '$bln2'");
			$hitung = mysql_num_rows($qry);
			if ($hitung >=1) {
				return true;
			}
			else{
				return false;
			}
		}
		public function hitung_total_penjualan(){
			$qry = mysql_query("SELECT sum(dpen.subtotal) as jumlah FROM penjualan pen
				JOIN d_penjualan dpen ON pen.kd_penjualan = dpen.kd_penjualan
				JOIN barang bar ON dpen.kd_barang = bar.kd_barang");
			$pecah = mysql_fetch_array($qry);
			$subtotal = $pecah['jumlah'];
			return $subtotal;
		}
		public function tampil_penjualan(){
			$qry = mysql_query("SELECT * FROM penjualan pen
				JOIN d_penjualan dpen ON pen.kd_penjualan = dpen.kd_penjualan
				JOIN barang bar ON dpen.kd_barang = bar.kd_barang ");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}
		public function cek_penjualan(){
			$qry = mysql_query("SELECT * FROM penjualan pen
				JOIN d_penjualan dpen ON pen.kd_penjualan = dpen.kd_penjualan
				JOIN barang bar ON dpen.kd_barang = bar.kd_barang");
			$hitung = mysql_num_rows($qry);
			if ($hitung >=1) {
				return true;
			}
			else{
				return false;
			}
		}
		public function hitung_total_penjualan_bulan($bln1,$bln2){
			$qry = mysql_query("SELECT sum(dpen.subtotal) as jumlah FROM penjualan pen
				JOIN d_penjualan dpen ON pen.kd_penjualan = dpen.kd_penjualan
				JOIN barang bar ON dpen.kd_barang = bar.kd_barang
				WHERE pen.tgl_penjualan BETWEEN '$bln1' AND '$bln2'");
			$pecah = mysql_fetch_array($qry);
			$subtotal = $pecah['jumlah'];
			return $subtotal;
		}
		//end penjualan

		public function tampil_pembelian_bulan($bln1,$bln2){
			$qry = mysql_query("SELECT * FROM supplier sup
				JOIN pembelian pem ON sup.kd_supplier = pem.kd_supplier
				JOIN d_pembelian dpem ON pem.kd_pembelian = dpem.kd_pembelian
				JOIN barang_pembelian barpem ON dpem.kd_barang_beli = barpem.kd_barang_beli 
				WHERE pem.tgl_pembelian BETWEEN '$bln1' AND '$bln2'");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}
		public function cek_pembelian_bulan($bln1,$bln2){
			$qry = mysql_query("SELECT * FROM supplier sup
				JOIN pembelian pem ON sup.kd_supplier = pem.kd_supplier
				JOIN d_pembelian dpem ON pem.kd_pembelian = dpem.kd_pembelian
				JOIN barang_pembelian barpem ON dpem.kd_barang_beli = barpem.kd_barang_beli 
				WHERE pem.tgl_pembelian BETWEEN '$bln1' AND '$bln2'");
			$hitung = mysql_num_rows($qry);
			if ($hitung >=1) {
				return true;
			}
			else{
				return false;
			}
		}
		public function hitung_total_pembelian_bulan($bln1,$bln2){
			$qry = mysql_query("SELECT sum(dpem.subtotal) as jumlah FROM supplier sup
				JOIN pembelian pem ON sup.kd_supplier = pem.kd_supplier
				JOIN d_pembelian dpem ON pem.kd_pembelian = dpem.kd_pembelian
				JOIN barang_pembelian barpem ON dpem.kd_barang_beli = barpem.kd_barang_beli 
				WHERE pem.tgl_pembelian BETWEEN '$bln1' AND '$bln2'");
			$pecah = mysql_fetch_array($qry);
			$subtotal = $pecah['jumlah'];
			return $subtotal;
		}
		public function hitung_total_pembelian(){
			$qry = mysql_query("SELECT sum(dpem.subtotal) as jumlah FROM supplier sup
				JOIN pembelian pem ON sup.kd_supplier = pem.kd_supplier
				JOIN d_pembelian dpem ON pem.kd_pembelian = dpem.kd_pembelian
				JOIN barang_pembelian barpem ON dpem.kd_barang_beli = barpem.kd_barang_beli");
			$pecah = mysql_fetch_array($qry);
			$subtotal = $pecah['jumlah'];
			return $subtotal;
		}
		public function tampil_pembelian(){
			$qry = mysql_query("SELECT * FROM supplier sup
				JOIN pembelian pem ON sup.kd_supplier = pem.kd_supplier
				JOIN d_pembelian dpem ON pem.kd_pembelian = dpem.kd_pembelian
				JOIN barang_pembelian barpem ON dpem.kd_barang_beli = barpem.kd_barang_beli");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}
		public function cek_pembelian(){
			$qry = mysql_query("SELECT * FROM supplier sup
				JOIN pembelian pem ON sup.kd_supplier = pem.kd_supplier
				JOIN d_pembelian dpem ON pem.kd_pembelian = dpem.kd_pembelian
				JOIN barang_pembelian barpem ON dpem.kd_barang_beli = barpem.kd_barang_beli");
			$hitung = mysql_num_rows($qry);
			if ($hitung >=1) {
				return true;
			}
			else{
				return false;
			}
		}
		//end pembelian
		public function hitung_profit_bulan(){
			
		}
		public function hitung_profit_semua(){

		}
	}
	class Cetak_Laporan{
		public function laporan_penjualan_bulan($bln1,$bln2){
			$qry = mysql_query("SELECT * FROM penjualan pen
				JOIN d_penjualan dpen ON pen.kd_penjualan = dpen.kd_penjualan
				JOIN barang bar ON dpen.kd_barang = bar.kd_barang 
				WHERE pen.tgl_penjualan BETWEEN '$bln1' AND '$bln2'");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}
		public function laporan_semua_penjualan(){
			$qry = mysql_query("SELECT * FROM penjualan pen
				JOIN d_penjualan dpen ON pen.kd_penjualan = dpen.kd_penjualan
				JOIN barang bar ON dpen.kd_barang = bar.kd_barang ");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}
		public function laporan_pembelian_bulan($bln1,$bln2){
			$qry = mysql_query("SELECT * FROM supplier sup
				JOIN pembelian pem ON sup.kd_supplier = pem.kd_supplier
				JOIN d_pembelian dpem ON pem.kd_pembelian = dpem.kd_pembelian
				JOIN barang_pembelian barpem ON dpem.kd_barang_beli = barpem.kd_barang_beli 
				WHERE pem.tgl_pembelian BETWEEN '$bln1' AND '$bln2'");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}public function laporan_semua_pembelian(){
			$qry = mysql_query("SELECT * FROM supplier sup
				JOIN pembelian pem ON sup.kd_supplier = pem.kd_supplier
				JOIN d_pembelian dpem ON pem.kd_pembelian = dpem.kd_pembelian
				JOIN barang_pembelian barpem ON dpem.kd_barang_beli = barpem.kd_barang_beli");
			while ($pecah = mysql_fetch_array($qry)) {
				$data[]=$pecah;
			}
			$hitung = mysql_num_rows($qry);
			if ($hitung > 0) {
				return $data;
			}
			else{
				error_reporting(0);
			}
		}
	}
	class Perusahaan{
		public function tampil_perusahaan(){
			$qry = mysql_query("SELECT * FROM perusahaan WHERE kd_perusahaan = '1'");
			$pecah = mysql_fetch_assoc($qry);
			return $pecah;
		}
		public function simpan_perusahaan($nama,$alamat,$pemilik,$kota){
			mysql_query("UPDATE perusahaan SET nama_perusahaan='$nama',alamat='$alamat', pemilik='$pemilik', kota='$kota' WHERE kd_perusahaan ='1' ");
		}
	}
	class Dashboard{
		public function penjualan_hariini(){
		$hari = date("Y-m-d");
			$qry = mysql_query("SELECT * FROM penjualan WHERE tgl_penjualan = '$hari'");
			$hitung = mysql_num_rows($qry);
			return $hitung;
		}
		public function pembelian_hariini(){
		$hari = date("Y-m-d");
			$qry = mysql_query("SELECT * FROM pembelian WHERE tgl_pembelian = '$hari'");
			$hitung = mysql_num_rows($qry);
			return $hitung;
		}
	}
	$DataBase = new DataBase();
	$DataBase->sambungkan();
	$admin = new Admin();
	$barang = new Barang();
	$supplier = new Supplier();
	$pembelian = new Pembelian();
	$penjualan = new Penjualan();
	$nota = new Nota();
	$laporan = new Laporan();
	$cetaklaporan =  new Cetak_Laporan();
	$perusahaan = new Perusahaan();
	$dashboard = new Dashboard();
?>