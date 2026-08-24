<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link">
        <img src="<?= base_url(); ?>assets\img\faces\admin.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Managemen Magang</span>
    </a>

    <div class="sidebar">

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                            <a href="<?= base_url('dashboard'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'DashboardController') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                <!-- BERANDA -->
                <li class="nav-item has-treeview <?= ($this->uri->segment(2) == 'FormTestimoniController' || $this->uri->segment(2) == 'FormKeunggulanController') ? 'menu-open' : ''; ?>">
                    <a href="#" class="nav-link <?= ($this->uri->segment(2) == 'FormTestimoniController' || $this->uri->segment(2) == 'FormKeunggulanController') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            BERANDA
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('students'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'StudentController') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>Students</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('supervisors'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'SupervisorController') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>Supervisors</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('consultations'); ?>" class="nav-link <?= ($this->uri->segment(2) == 'ConsultationController') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-comments"></i>
                                <p>Consultations</p>
                            </a>
                    </ul>
                </li>

                

            </ul>
        </nav>
    </div>
</aside>