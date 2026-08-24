<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - Booking Konsultasi' : 'Booking Konsultasi' ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <script>
        // Dipakai oleh semua file JS di assets/js untuk membangun URL AJAX ke controller
        var BASE_URL = "<?= base_url() ?>";
    </script>
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-3 shadow-sm">
    <span class="navbar-brand mb-0 h1">
        <i class="bi bi-calendar2-check"></i> Sistem Booking Konsultasi/Bimbingan
    </span>
</nav>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-dark text-white p-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?= (isset($active) && $active == 'dashboard') ? 'active-menu' : '' ?>" href="<?= base_url('Dashboard') ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= (isset($active) && $active == 'students') ? 'active-menu' : '' ?>" href="<?= base_url('Students') ?>">
                    <i class="bi bi-people"></i> Master Student
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= (isset($active) && $active == 'supervisors') ? 'active-menu' : '' ?>" href="<?= base_url('Supervisors') ?>">
                    <i class="bi bi-person-badge"></i> Master Supervisor
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= (isset($active) && $active == 'consultations') ? 'active-menu' : '' ?>" href="<?= base_url('Consultations') ?>">
                    <i class="bi bi-journal-text"></i> Booking Konsultasi
                </a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content-wrapper p-4 w-100">
