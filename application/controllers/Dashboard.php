<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Jakarta');

        $this->load->database();
        $this->load->helper('url');
    }

    public function index()
    {
        // ==========================================
        // DATA DASHBOARD
        // ==========================================

        $data['title'] = 'Dashboard';


        // ==========================================
        // TOTAL STUDENT
        // ==========================================

        $data['total_student'] = $this->db
            ->count_all('students');


        // ==========================================
        // TOTAL SUPERVISOR
        // ==========================================

        $data['total_supervisor'] = $this->db
            ->count_all('supervisors');


        // ==========================================
        // TOTAL CONSULTATION
        // ==========================================

        $data['total_booking'] = $this->db
            ->count_all('consultations');


        // ==========================================
        // CONSULTATION HARI INI
        // ==========================================

        $data['total_today'] = $this->db
            ->where(
                'consultation_date',
                date('Y-m-d')
            )
            ->count_all_results('consultations');


        // ==========================================
        // STATUS PENDING
        // ==========================================

        $data['pending'] = $this->db
            ->where('status', 'Pending')
            ->count_all_results('consultations');


        // ==========================================
        // STATUS APPROVED
        // ==========================================

        $data['approved'] = $this->db
            ->where('status', 'Approved')
            ->count_all_results('consultations');


        // ==========================================
        // STATUS REJECTED
        // ==========================================

        $data['rejected'] = $this->db
            ->where('status', 'Rejected')
            ->count_all_results('consultations');


        // ==========================================
        // STATUS COMPLETED
        // ==========================================

        $data['completed'] = $this->db
            ->where('status', 'Completed')
            ->count_all_results('consultations');


        // ==========================================
        // DATA CONSULTATION HARI INI
        // ==========================================

        $this->db->select('
            consultations.id,
            consultations.consultation_date,
            consultations.start_time,
            consultations.end_time,
            consultations.topic,
            consultations.status,

            students.student_code,
            students.name AS student_name,

            supervisors.supervisor_code,
            supervisors.name AS supervisor_name
        ');

        $this->db->from('consultations');

        $this->db->join(
            'students',
            'students.id = consultations.student_id',
            'inner'
        );

        $this->db->join(
            'supervisors',
            'supervisors.id = consultations.supervisor_id',
            'inner'
        );

        $this->db->where(
            'consultations.consultation_date',
            date('Y-m-d')
        );

        $this->db->order_by(
            'consultations.start_time',
            'ASC'
        );

        $this->db->limit(10);

        $data['bookings_today'] = $this->db
            ->get()
            ->result();


        // ==========================================
        // DATA CONSULTATION TERBARU
        // ==========================================

        $this->db->select('
            consultations.id,
            consultations.consultation_date,
            consultations.start_time,
            consultations.end_time,
            consultations.topic,
            consultations.status,

            students.student_code,
            students.name AS student_name,

            supervisors.supervisor_code,
            supervisors.name AS supervisor_name
        ');

        $this->db->from('consultations');

        $this->db->join(
            'students',
            'students.id = consultations.student_id',
            'inner'
        );

        $this->db->join(
            'supervisors',
            'supervisors.id = consultations.supervisor_id',
            'inner'
        );

        $this->db->order_by(
            'consultations.created_at',
            'DESC'
        );

        $this->db->limit(10);

        $data['recent_bookings'] = $this->db
            ->get()
            ->result();


        $data['contents'] = $this->load->view(
            'dashboardView',
            $data,
            true   // TRUE = return sebagai string, bukan langsung di-print
        );

        $this->load->view('main_template', $data);
    }
}