<div class="content-wrapper">
<section class="content-header"><div class="container-fluid"><h1>Detail Booking Konsultasi</h1></div></section>
<section class="content"><div class="container-fluid"><div class="card card-primary"><div class="card-body">
<div class="mb-3"><a href="<?=site_url('consultations')?>" class="btn btn-sm btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a></div>
<table class="table table-bordered">
<tr><th width="220">ID</th><td><?=html_escape($row->id)?></td></tr>
<tr><th>Student</th><td><?=html_escape($row->student_code.' - '.$row->student_name)?></td></tr>
<tr><th>Supervisor</th><td><?=html_escape($row->supervisor_code.' - '.$row->supervisor_name)?></td></tr>
<tr><th>Tanggal</th><td><?=html_escape($row->consultation_date)?></td></tr>
<tr><th>Jam</th><td><?=html_escape(substr($row->start_time,0,5).' - '.substr($row->end_time,0,5))?></td></tr>
<tr><th>Topik</th><td><?=html_escape($row->topic)?></td></tr>
<tr><th>Status</th><td>
<?php
$badge = "secondary";
if ($row->status == "Pending") $badge = "warning";
elseif ($row->status == "Approved") $badge = "success";
elseif ($row->status == "Rejected") $badge = "danger";
elseif ($row->status == "Completed") $badge = "primary";
?>
<span class="badge badge-<?=$badge?>"><?=html_escape($row->status)?></span>
</td></tr>
<tr><th>Catatan</th><td><?=nl2br(html_escape($row->notes))?></td></tr>
<tr><th>Dibuat</th><td><?=html_escape($row->created_at)?></td></tr>
</table>
</div></div></div></section></div>