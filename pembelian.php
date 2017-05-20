<?php  
    if (isset($_GET['hapus'])) {
        $pembelian->hapus_pembelian($_GET['hapus']);
        echo "<script>location='index.php?page=pembelian';</script>";
    }

?>
<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Data Pembelian
            </div>
            <div class="panel-body">
                <div class="table">
                    <table class="table table-striped table-bordered table-hover" id="tabelku">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kd Pembelian</th>
                                <th>Tgl Pembelian</th>
                                <th>Kd Supplier</th>
                                <th>Nama Supplier</th>
                                <th>Jumlah Pembelian</th>
                                <th>Total Pembelian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                $pem = $pembelian->tampil_pembelian();
                                foreach ($pem as $index => $data) {
                                    $jumlahb = $pembelian->hitung_item_pembelian($data['kd_pembelian']);
                            ?>
                            <tr class="odd gradeX">
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $data['kd_pembelian']; ?></td>
                                <td><?php echo $data['tgl_pembelian']; ?></td>
                                <td><?php echo $data['kd_supplier']; ?></td>
                                <td><?php echo $data['nama_supplier']; ?></td>
                                <td><?php echo $jumlahb['jumlah']; ?></td>
                                <td>Rp. <?php echo number_format($data['total_pembelian']); ?></td>
                                <td>
                                    <a href="nota/cetakdetailpembelian.php?kdpembelian=<?php echo $data['kd_pembelian']; ?>" target="_BLANK" class="btn btn-info btn-xs"><i class="fa fa-search"></i> Detail</a>
                                    
                                    <?php 
                                    $cek = $pembelian->cek_hapuspembelian($data['kd_pembelian']);
                                    if ($cek === true): ?>
                                    <a href="index.php?page=pembelian&hapus=<?php echo $data['kd_pembelian']; ?>" class="btn btn-danger btn-xs" id="alertHapus"><i class="fa fa-trash"></i> Hapus</a>                                        
                                    <?php endif ?>
                                    <?php if ($cek === false): ?>
                                    <a href="index.php?page=pembelian&hapus=<?php echo $data['kd_pembelian']; ?>" class="btn btn-danger btn-xs" id="alertHapus" disabled="disabled"><i class="fa fa-trash"></i> Hapus</a>
                                    <?php endif ?>
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