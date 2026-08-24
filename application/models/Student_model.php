<?php
class Student_model extends CI_Model
{
    /**
     * Ambil semua data student untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per kelas
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('students');
        $sql = "SELECT  id, 
                        student_code,     
                        name, 
                        email,
                        phone,
                        class_name,
                        created_at
                FROM students 
                WHERE " . $data['filtervalue'] . " LIKE '%" . $data['filtertext'] . "%' 
                LIMIT " . $data['length'] . " OFFSET " . $data['start'];

        $query = $this->db->query($sql);
        $data = $query->result();
        $total = $queryall->num_rows();
        $dataRecord = array(
            "RecordsTotal" => $total,
            "RecordsFiltered" => $total,
            "Data" => $data,
        );
        return $dataRecord;
    }

    /**
     * Ambil data student berdasarkan ID (untuk edit)
     * @param int $id - ID student
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql = "SELECT * FROM students WHERE id='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data student baru
     * @param array $data - Data student
     * @return boolean
     * 
     * @development: 
     * - Cek duplikasi student_code/email
     * - Validasi format email
     * - Validasi nomor telepon
     */
    public function insertData($data)
    {
        unset($data['id']);
        unset($data['created_at']);
        $query = $this->db->insert('students', $data);
        return $query;
    }

    /**
     * Update data student
     * @param array $data - Data student (dengan id)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Validasi data sebelum update
     */
    public function updateData($data)
    {
        unset($data['created_at']);
        $this->db->where('id', $data['id']);
        $query = $this->db->update('students', $data);
        return array('result' => $query);
    }

    /**
     * Delete data student
     * @param array $data (id)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah student memiliki relasi (consultations)
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id', $data['id']);
        $success = $this->db->delete('students');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }

    /**
     * Cek apakah nama sudah ada (validasi duplikat)
     * @param string $name - Nama yang dicek
     * @return string "Data Sama" / "OK"
     * 
     * @development: 
     * - Tambah pengecualian saat mode edit (kecuali id sendiri)
     */
    public function checkNama($name)
    {
        $sql = "SELECT * FROM students WHERE name='$name' ";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data student (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah sorting (nama, kelas)
     * - Hanya tampilkan student aktif
     */
    public function getAllStudents()
    {
        $query = $this->db->get('students');
        return $query->result();
    }
}