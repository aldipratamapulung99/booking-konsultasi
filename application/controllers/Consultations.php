<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Consultations extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Load model & helper
    function __construct()
    {
        parent::__construct();
        $this->load->model('Consultation_model', 'model');
        $this->load->model('Student_model', 'student_model');
        $this->load->model('Supervisor_model', 'supervisor_model');
        $this->load->helper('url');
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen data consultation
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title']       = 'Data Consultations';
        $data['students']    = $this->student_model->getAllStudents();
        $data['supervisors'] = $this->supervisor_model->getAllSupervisors();
        $data['contents']    = $this->load->view('consultationsView', $data, true);
        $this->load->view('main_template', $data);
    }

    // ===================== HALAMAN DETAIL =====================
    // Menampilkan detail 1 data consultation (read-only, join student & supervisor)
    // @param int $id
    function detail($id)
    {
        $rows = $this->model->getDetailById($id);

        if (empty($rows)) {
            show_404();
            return;
        }

        $data['title']    = 'Detail Konsultasi';
        $data['row']      = $rows[0];
        $data['contents'] = $this->load->view('consultationsDetailView', $data, true);
        $this->load->view('main_template', $data);
    }

    // ===================== AMBIL DATA UNTUK DATATABLE =====================
    // Server-side DataTable
    // @param POST: start, length, filtervalue, filtertext
    // @return JSON
    function getData()
    {
        $data = array(
            'start'       => $_POST['start'],
            'length'      => $_POST['length'],
            'filtervalue' => $_POST['filtervalue'],
            'filtertext'  => $_POST['filtertext'],
        );
        $res = $this->model->getDataAll($data);
        echo json_encode($res);
    }

    // ===================== AMBIL DATA BY ID =====================
    // Untuk mengedit data consultation
    // @param POST: id
    // @return JSON
    // @development: Tambah validasi jika data tidak ditemukan
    function getDataSelect()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->getDataId($data['id']);
        echo json_encode($res);
    }

    // ===================== SAVE DATA =====================
    // Insert data consultation baru (default status Pending)
    // @param POST: student_id, supervisor_id, consultation_date, start_time, end_time, topic, status, notes
    // @return JSON (result, id) / (result, message) jika gagal
    function save()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->db->db_debug = false;

        // Validasi bentrok jadwal di server (tidak bisa dilewati walau endpoint
        // dipanggil langsung tanpa lewat checkConflict() dari frontend)
        $conflict = $this->model->checkConflict($data);
        if ($conflict['conflict']) {
            echo json_encode(["result" => false, "message" => $conflict['message']]);
            return;
        }

        $insert = $this->model->insertData($data);

        if (!$insert) {
            $error = $this->db->error();
            echo json_encode(["result" => false, "message" => $error['message']]);
            return;
        }

        echo json_encode(["result" => true, "id" => $this->db->insert_id()]);
    }

    // ===================== UPDATE DATA =====================
    // Update data consultation
    // @param POST: id, student_id, supervisor_id, consultation_date, start_time, end_time, topic, status, notes
    // @return JSON (result) / (result, message) jika gagal
    function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->db->db_debug = false;

        // Validasi bentrok jadwal di server (kecuali dengan data miliknya sendiri).
        // checkConflict() memakai $data['id'] untuk mengecualikan record ini sendiri.
        $conflict = $this->model->checkConflict($data);
        if ($conflict['conflict']) {
            echo json_encode(["result" => false, "message" => $conflict['message']]);
            return;
        }

        $res = $this->model->updateData($data);

        if (!$res['result']) {
            $error = $this->db->error();
            echo json_encode(["result" => false, "message" => $error['message']]);
            return;
        }

        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data consultation
    // @param POST: id
    // @return JSON (result, message)
    function delete()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->deleteData(['id' => $data['id']]);
        echo json_encode($res);
    }

    // ===================== CEK BENTROK JADWAL =====================
    // Validasi bentrok jadwal supervisor & student sebelum save/update
    // @param POST: id, student_id, supervisor_id, consultation_date, start_time, end_time
    // @return JSON (conflict, message)
    function checkConflict()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->checkConflict($data);
        echo json_encode($res);
    }

    // ===================== UPDATE STATUS =====================
    // Ubah status consultation (Pending/Approved/Rejected/Completed)
    // @param POST: id, status
    // @return JSON (result, message)
    function updateStatus()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->updateStatus($data['id'], $data['status']);
        echo json_encode($res);
    }
}