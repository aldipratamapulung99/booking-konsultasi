<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supervisors extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Load model & helper
    function __construct()
    {
        parent::__construct();
        $this->load->model('Supervisor_model', 'model');
        $this->load->helper('url');
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen data supervisor
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title']    = 'Data Supervisor';
        $data['contents'] = $this->load->view('supervisorsView', $data, true);
        $this->load->view('main_template', $data);
    }

    // ===================== HALAMAN DETAIL =====================
    // Menampilkan detail 1 data supervisor (read-only)
    // @param int $id
    function detail($id)
    {
        $rows = $this->model->getDataId($id);

        if (empty($rows)) {
            show_404();
            return;
        }

        $data['title']    = 'Detail Supervisor';
        $data['row']      = $rows[0];
        $data['contents'] = $this->load->view('supervisorsDetailView', $data, true);
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
    // Untuk mengedit data supervisor
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
    // Insert data supervisor baru
    // @param POST: supervisor_code, name, email, phone, specialization
    // @return JSON (result)
    // @development: Cek duplikasi supervisor_code
    function save()
    {
        $data   = json_decode(file_get_contents('php://input'), true);
        $insert = $this->model->insertData($data);
        echo json_encode(["result" => $insert]);
    }

    // ===================== UPDATE DATA =====================
    // Update data supervisor
    // @param POST: id, supervisor_code, name, email, phone, specialization
    // @return JSON (result)
    function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data supervisor
    // @param POST: id
    // @return JSON (result, message)
    // @development: Cek relasi konsultasi sebelum hapus
    function delete()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->deleteData(['id' => $data['id']]);
        echo json_encode($res);
    }

    // ===================== CEK DUPLIKASI =====================
    // Validasi supervisor_code saat insert/update
    // @param POST: supervisor_code
    // @return JSON (res)
    function checkCode()
    {
        $data  = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkCode($data['supervisor_code']);
        echo json_encode(['res' => $check]);
    }
}