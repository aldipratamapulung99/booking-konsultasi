<?php
class Supervisor_model extends CI_Model
{
    /**
     * Ambil semua data supervisor untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per spesialisasi
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('supervisors');
        $sql = "SELECT  id, 
                        supervisor_code,     
                        name, 
                        email,
                        phone,
                        specialization,
                        created_at
                FROM supervisors 
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
     * Ambil data supervisor berdasarkan ID (untuk edit)
     * @param int $id - ID supervisor
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql = "SELECT * FROM supervisors WHERE id='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data supervisor baru
     * @param array $data - Data supervisor
     * @return boolean
     * 
     * @development: 
     * - Cek duplikasi supervisor_code/email
     * - Validasi format email
     * - Validasi nomor telepon
     */
    public function insertData($data)
    {
        unset($data['id']);
        unset($data['created_at']);
        $query = $this->db->insert('supervisors', $data);
        return $query;
    }

    /**
     * Update data supervisor
     * @param array $data - Data supervisor (dengan id)
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
        $query = $this->db->update('supervisors', $data);
        return array('result' => $query);
    }

    /**
     * Delete data supervisor
     * @param array $data (id)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah supervisor memiliki relasi (consultations)
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id', $data['id']);
        $success = $this->db->delete('supervisors');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }

    /**
     * Cek apakah kode supervisor sudah ada (validasi duplikat)
     * @param string $code - Kode supervisor yang dicek
     * @return string "Data Sama" / "OK"
     * 
     * @development: 
     * - Tambah pengecualian saat mode edit (kecuali id sendiri)
     */
    public function checkCode($code)
    {
        $sql = "SELECT * FROM supervisors WHERE supervisor_code='$code' ";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data supervisor (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah sorting (nama, spesialisasi)
     * - Hanya tampilkan supervisor aktif
     */
    public function getAllSupervisors()
    {
        $query = $this->db->get('supervisors');
        return $query->result();
    }
}