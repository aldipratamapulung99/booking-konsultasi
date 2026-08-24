-- =========================================================
-- Database: booking_konsultasi
-- Sistem Booking Jadwal Konsultasi/Bimbingan
-- Jalankan bagian CREATE DATABASE ini terpisah lewat psql
-- (tidak semua tool bisa CREATE DATABASE dari dalam 1 file transaksi)
-- =========================================================

-- 1. Buat database (jalankan sebagai superuser / role yang punya izin)
-- CREATE DATABASE booking_konsultasi;

-- 2. Setelah database dibuat, connect ke database-nya:
-- \c booking_konsultasi

-- =========================================================
-- Tabel 1: students
-- =========================================================
CREATE TABLE students (
    id SERIAL PRIMARY KEY,
    student_code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    class_name VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- Tabel 2: supervisors
-- =========================================================
CREATE TABLE supervisors (
    id SERIAL PRIMARY KEY,
    supervisor_code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    specialization VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================================
-- Tabel 3: consultations (relasi ke students & supervisors)
-- =========================================================
CREATE TABLE consultations (
    id SERIAL PRIMARY KEY,
    student_id INTEGER NOT NULL REFERENCES students(id) ON DELETE CASCADE,
    supervisor_id INTEGER NOT NULL REFERENCES supervisors(id) ON DELETE CASCADE,
    consultation_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    topic VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Pending'
        CHECK (status IN ('Pending','Approved','Rejected','Completed')),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_time_valid CHECK (start_time < end_time)
);

-- Index untuk mempercepat pengecekan bentrok jadwal
CREATE INDEX idx_consult_supervisor_date ON consultations (supervisor_id, consultation_date);
CREATE INDEX idx_consult_student_date ON consultations (student_id, consultation_date);

-- =========================================================
-- Data contoh (opsional, untuk testing)
-- =========================================================
INSERT INTO students (student_code, name, email, phone, class_name) VALUES
('STD001', 'Ahmad Fauzan', 'ahmad.fauzan@mail.com', '081234567891', 'TI-3A'),
('STD002', 'Siti Nurhaliza', 'siti.nurhaliza@mail.com', '081234567892', 'TI-3B'),
('STD003', 'Budi Santoso', 'budi.santoso@mail.com', '081234567893', 'SI-2A');

INSERT INTO supervisors (supervisor_code, name, email, phone, specialization) VALUES
('SPV001', 'Dr. Rina Wijaya', 'rina.wijaya@kampus.ac.id', '081298765431', 'Rekayasa Perangkat Lunak'),
('SPV002', 'Ir. Bambang Sutrisno', 'bambang.sutrisno@kampus.ac.id', '081298765432', 'Basis Data'),
('SPV003', 'Dra. Maya Kusuma', 'maya.kusuma@kampus.ac.id', '081298765433', 'Jaringan Komputer');

INSERT INTO consultations (student_id, supervisor_id, consultation_date, start_time, end_time, topic, status, notes) VALUES
(1, 1, CURRENT_DATE, '09:00', '10:00', 'Bimbingan Bab 1 Skripsi', 'Approved', 'Bawa draft bab 1'),
(2, 2, CURRENT_DATE, '10:00', '11:00', 'Konsultasi Desain Database', 'Pending', NULL),
(3, 1, CURRENT_DATE + 1, '13:00', '14:00', 'Revisi Metodologi Penelitian', 'Pending', NULL);
