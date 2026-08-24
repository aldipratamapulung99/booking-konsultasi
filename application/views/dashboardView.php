<div class="content-wrapper">

    <!-- ================= HEADER ================= -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>Dashboard</h3>
                    <small class="text-muted">Sistem Booking Konsultasi dan Bimbingan</small>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <!-- =====================================================
                 STATISTIK UTAMA
            ====================================================== -->
            <div class="row">

                <!-- TOTAL STUDENT -->
                <div class="col-lg-3 col-6">
                    <a href="<?php echo base_url('students'); ?>" class="text-decoration-none">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo $total_student; ?></h3>
                                <p>Total Student</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <span class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- TOTAL SUPERVISOR -->
                <div class="col-lg-3 col-6">
                    <a href="<?php echo base_url('supervisors'); ?>" class="text-decoration-none">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $total_supervisor; ?></h3>
                                <p>Total Supervisor</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <span class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- TOTAL BOOKING -->
                <div class="col-lg-3 col-6">
                    <a href="<?php echo base_url('consultations'); ?>" class="text-decoration-none">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo $total_booking; ?></h3>
                                <p>Total Booking</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <span class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- BOOKING HARI INI -->
                <div class="col-lg-3 col-6">
                    <a href="<?php echo base_url('consultations') . '?filter_col=' . urlencode('consultations.consultation_date') . '&filter_text=' . urlencode(date('Y-m-d')); ?>" class="text-decoration-none">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?php echo $total_today; ?></h3>
                                <p>Booking Hari Ini</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <span class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </span>
                        </div>
                    </a>
                </div>

            </div>

            <!-- =====================================================
                 STATUS BOOKING
            ====================================================== -->
            <div class="row">

                <!-- PENDING -->
                <div class="col-lg-3 col-6">
                    <a href="<?php echo base_url('consultations') . '?filter_col=' . urlencode('consultations.status') . '&filter_text=Pending'; ?>" class="text-decoration-none">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3><?php echo $pending; ?></h3>
                                <p>Pending</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- APPROVED -->
                <div class="col-lg-3 col-6">
                    <a href="<?php echo base_url('consultations') . '?filter_col=' . urlencode('consultations.status') . '&filter_text=Approved'; ?>" class="text-decoration-none">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?php echo $approved; ?></h3>
                                <p>Approved</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <span class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- REJECTED -->
                <div class="col-lg-3 col-6">
                    <a href="<?php echo base_url('consultations') . '?filter_col=' . urlencode('consultations.status') . '&filter_text=Rejected'; ?>" class="text-decoration-none">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?php echo $rejected; ?></h3>
                                <p>Rejected</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <span class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- COMPLETED -->
                <div class="col-lg-3 col-6">
                    <a href="<?php echo base_url('consultations') . '?filter_col=' . urlencode('consultations.status') . '&filter_text=Completed'; ?>" class="text-decoration-none">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $completed; ?></h3>
                                <p>Completed</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <span class="small-box-footer">
                                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                            </span>
                        </div>
                    </a>
                </div>

            </div>

            <!-- =====================================================
                 BOOKING HARI INI
            ====================================================== -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-day"></i> Booking Hari Ini
                    </h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Student</th>
                                <th>Supervisor</th>
                                <th>Jam</th>
                                <th>Topik</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($bookings_today)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($bookings_today as $row) : ?>
                                    <tr style="cursor: pointer;" onclick="window.location.href='<?php echo site_url('consultations/detail/'); ?><?php echo $row->id; ?>'">
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <strong><?php echo $row->student_name; ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo $row->student_code; ?></small>
                                        </td>
                                        <td>
                                            <strong><?php echo $row->supervisor_name; ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo $row->supervisor_code; ?></small>
                                        </td>
                                        <td>
                                            <?php echo date('H:i', strtotime($row->start_time)); ?>
                                            -
                                            <?php echo date('H:i', strtotime($row->end_time)); ?>
                                        </td>
                                        <td><?php echo $row->topic; ?></td>
                                        <td>
                                            <?php
                                            if ($row->status == 'Pending') {
                                                echo '<span class="badge badge-warning">Pending</span>';
                                            } elseif ($row->status == 'Approved') {
                                                echo '<span class="badge badge-primary">Approved</span>';
                                            } elseif ($row->status == 'Rejected') {
                                                echo '<span class="badge badge-danger">Rejected</span>';
                                            } elseif ($row->status == 'Completed') {
                                                echo '<span class="badge badge-success">Completed</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada booking hari ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- =====================================================
                 BOOKING TERBARU
            ====================================================== -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i> Riwayat Booking 
                    </h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Tanggal</th>
                                <th>Student</th>
                                <th>Supervisor</th>
                                <th>Topik</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_bookings)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($recent_bookings as $row) : ?>
                                    <tr style="cursor: pointer;" onclick="window.location.href='<?php echo site_url('consultations/detail/'); ?><?php echo $row->id; ?>'">
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($row->consultation_date)); ?></td>
                                        <td>
                                            <?php echo $row->student_name; ?>
                                            <br>
                                            <small class="text-muted"><?php echo $row->student_code; ?></small>
                                        </td>
                                        <td>
                                            <?php echo $row->supervisor_name; ?>
                                            <br>
                                            <small class="text-muted"><?php echo $row->supervisor_code; ?></small>
                                        </td>
                                        <td><?php echo $row->topic; ?></td>
                                        <td>
                                            <?php
                                            if ($row->status == 'Pending') {
                                                echo '<span class="badge badge-warning">Pending</span>';
                                            } elseif ($row->status == 'Approved') {
                                                echo '<span class="badge badge-primary">Approved</span>';
                                            } elseif ($row->status == 'Rejected') {
                                                echo '<span class="badge badge-danger">Rejected</span>';
                                            } elseif ($row->status == 'Completed') {
                                                echo '<span class="badge badge-success">Completed</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data booking.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

</div>