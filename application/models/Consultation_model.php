<?php
class Consultation_model extends CI_Model
{
    /**
     * Ambil semua data consultation untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per status/tanggal
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('consultations');
        $sql = "SELECT  consultations.id, 
                        consultations.student_id,
                        consultations.supervisor_id,
                        consultations.consultation_date,
                        consultations.start_time,
                        consultations.end_time,
                        consultations.topic,
                        consultations.status,
                        consultations.notes,
                        consultations.created_at,
                        students.student_code,
                        students.name AS student_name,
                        supervisors.supervisor_code,
                        supervisors.name AS supervisor_name
                FROM consultations
                LEFT JOIN students ON students.id = consultations.student_id
                LEFT JOIN supervisors ON supervisors.id = consultations.supervisor_id
                WHERE " . $data['filtervalue'] . " LIKE '%" . $data['filtertext'] . "%' 
                ORDER BY consultations.id DESC
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
     * Ambil data consultation berdasarkan ID (untuk edit)
     * @param int $id - ID consultation
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql = "SELECT * FROM consultations WHERE id='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data consultation baru
     * @param array $data - Data consultation
     * @return boolean
     * 
     * @development: 
     * - Cek bentrok jadwal (pakai checkConflict sebelum insert)
     * - Validasi student & supervisor tersedia
     */
    public function insertData($data)
    {
        unset($data['id']);
        unset($data['created_at']);
        $query = $this->db->insert('consultations', $data);
        return $query;
    }

    /**
     * Update data consultation
     * @param array $data - Data consultation (dengan id)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Cek bentrok jadwal (kecuali id sendiri)
     */
    public function updateData($data)
    {
        unset($data['created_at']);
        $this->db->where('id', $data['id']);
        $query = $this->db->update('consultations', $data);
        return array('result' => $query);
    }

    /**
     * Delete data consultation
     * @param array $data (id)
     * @return array (result, message)
     * 
     * @development: 
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id', $data['id']);
        $success = $this->db->delete('consultations');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }

    /**
     * Cek bentrok jadwal supervisor & student
     * @param array $data (id, student_id, supervisor_id, consultation_date, start_time, end_time)
     * @return array (conflict, message)
     * 
     * @development: 
     * - Gunakan query builder
     */
    public function checkConflict($data)
    {
        $id    = isset($data['id']) ? $data['id'] : 0;
        $date  = $data['consultation_date'];
        $start = $data['start_time'];
        $end   = $data['end_time'];

        $sqlSupervisor = "SELECT id FROM consultations
                           WHERE supervisor_id = '" . $data['supervisor_id'] . "'
                           AND consultation_date = '" . $date . "'
                           AND start_time < '" . $end . "' AND end_time > '" . $start . "'";
        if ($id > 0) $sqlSupervisor .= " AND id <> '$id'";
        $sqlSupervisor .= " LIMIT 1";
        if ($this->db->query($sqlSupervisor)->num_rows() > 0) {
            return array('conflict' => true, 'message' => 'Jadwal supervisor bentrok.');
        }

        $sqlStudent = "SELECT id FROM consultations
                        WHERE student_id = '" . $data['student_id'] . "'
                        AND consultation_date = '" . $date . "'
                        AND start_time < '" . $end . "' AND end_time > '" . $start . "'";
        if ($id > 0) $sqlStudent .= " AND id <> '$id'";
        $sqlStudent .= " LIMIT 1";
        if ($this->db->query($sqlStudent)->num_rows() > 0) {
            return array('conflict' => true, 'message' => 'Jadwal student bentrok.');
        }

        return array('conflict' => false, 'message' => 'Jadwal tersedia.');
    }

    /**
     * Update status consultation (Pending/Approved/Rejected/Completed)
     * @param int $id
     * @param string $status
     * @return array (result, message)
     * 
     * @development: 
     * - Validasi status yang diperbolehkan
     */
    public function updateStatus($id, $status)
    {
        $this->db->where('id', $id);
        $success = $this->db->update('consultations', array('status' => $status));
        return array(
            'result' => $success,
            'message' => $success ? 'Status berhasil diperbarui.' : 'Status gagal diperbarui.'
        );
    }

    /**
     * Ambil data consultation lengkap (join student & supervisor) untuk halaman Detail
     * @param int $id - ID consultation
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     */
    public function getDetailById($id)
    {
        $sql = "SELECT
                    consultations.id,
                    consultations.consultation_date,
                    consultations.start_time,
                    consultations.end_time,
                    consultations.topic,
                    consultations.status,
                    consultations.notes,
                    consultations.created_at,
                    students.student_code,
                    students.name AS student_name,
                    supervisors.supervisor_code,
                    supervisors.name AS supervisor_name
                FROM consultations
                LEFT JOIN students ON students.id = consultations.student_id
                LEFT JOIN supervisors ON supervisors.id = consultations.supervisor_id
                WHERE consultations.id = '$id'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Ambil semua data consultation (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah sorting (tanggal, status)
     */
    public function getAllConsultations()
    {
        $query = $this->db->get('consultations');
        return $query->result();
    }
}