<?php  
    if (isset($_GET['hapus'])) {
        $admin->hapus_admin($_GET['hapus']);
        echo "<script>location='index.php?page=admin';</script>";
    }
?>
<div class="row">
    <div class="col-md-12">
    <!-- Advanced Tables -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Data Admin
            </div>
            <div class="panel-body">
                <div class="table">
                    <table class="table table-striped table-bordered table-hover" id="tabelku">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                $adm = $admin->tampil_admin();
                                foreach ($adm as $index => $data) {
                            ?>
                            <tr class="odd gradeX">
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $data['nama']; ?></td>
                                <td><?php echo $data['email']; ?></td>
                                <td><?php echo $data['password']; ?></td>
                                <td>
                                    <img src="gambar_admin/<?php echo $data['gambar']; ?>" width="90">
                                </td>
                                <td>
                                    <a href="index.php?page=ubahadmin&id=<?php echo $data['kd_admin']; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit</a>
                                    <a href="index.php?page=admin&hapus=<?php echo $data['kd_admin']; ?>" class="btn btn-danger btn-xs" id="alertHapus"><i class="fa fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>   
            </div>
            <div class="panel-footer">
                <a href="index.php?page=tambahadmin" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Admin</a>
            </div>
        </div>
        <!--End Advanced Tables -->
    </div>
</div>