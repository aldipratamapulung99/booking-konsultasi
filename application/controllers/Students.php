<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Students extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Load model & helper
    function __construct()
    {
        parent::__construct();
        $this->load->model('Student_model', 'model');
        $this->load->helper('url');
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen data student
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title']     = 'Data Students';
        $data['contents']  = $this->load->view('studentsView', $data, true);
        $this->load->view('main_template', $data);
    }

    // ===================== HALAMAN DETAIL =====================
    // Menampilkan detail 1 data student (read-only)
    // @param int $id
    function detail($id)
    {
        $rows = $this->model->getDataId($id);

        if (empty($rows)) {
            show_404();
            return;
        }

        $data['title']    = 'Detail Student';
        $data['row']      = $rows[0];
        $data['contents'] = $this->load->view('studentsDetailView', $data, true);
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
    // Untuk mengedit data student
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
    // Insert data student baru
    // @param POST: student_code, name, email, phone, class_name
    // @return JSON (result)
    // @development: Cek duplikasi student_code
    function save()
    {
        $data   = json_decode(file_get_contents('php://input'), true);
        $insert = $this->model->insertData($data);
        echo json_encode(["result" => $insert]);
    }

    // ===================== UPDATE DATA =====================
    // Update data student
    // @param POST: id, student_code, name, email, phone, class_name
    // @return JSON (result)
    function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data student
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
    // Validasi nama student saat insert/update
    // @param POST: name
    // @return JSON (res)
    function checkNama()
    {
        $data  = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkNama($data['name']);
        echo json_encode(['res' => $check]);
    }

    // ===================== AMBIL DATA STUDENT =====================
    // Untuk dropdown pilihan student
    // @return JSON (value, name)
    public function getKategori()
    {
        $data = $this->model->getAllStudents();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id,
                'name'  => $row->name
            ];
        }
        echo json_encode($result);
    }
}