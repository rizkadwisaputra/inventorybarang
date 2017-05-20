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
                                <th>Kd Penjualan</th>
                                <th>Tgl Penjualan</th>
                                <th>Item</th>
                                <th>Total Penjualan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  
                                $pen = $penjualan->tampil_penjualan();
                                foreach ($pen as $index => $data) {
                                    $jumlahb = $penjualan->hitung_item_penjualan($data['kd_penjualan']);
                            ?>
                            <tr class="odd gradeX">
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo $data['kd_penjualan']; ?></td>
                                <td><?php echo $data['tgl_penjualan']; ?></td>
                                <td><?php echo $jumlahb['jumlah']; ?></td>
                                <td>Rp. <?php echo number_format($data['total_penjualan']); ?></td>
                                <td>
                                    <a href="nota/cetakdetailpenjualan.php?kdpenjualan=<?php echo $data['kd_penjualan']; ?>" target="_BLANK" class="btn btn-info btn-xs"><i class="fa fa-search"></i> Detail</a>
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