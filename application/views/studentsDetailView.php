<div class="content-wrapper">
<section class="content-header"><div class="container-fluid"><h1>Detail Student</h1></div></section>
<section class="content"><div class="container-fluid"><div class="card card-primary"><div class="card-body">
<div class="mb-3"><a href="<?=site_url('students')?>" class="btn btn-sm btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a></div>
<table class="table table-bordered">
<tr><th width="220">ID</th><td><?=html_escape($row->id)?></td></tr>
<tr><th>Kode Student</th><td><?=html_escape($row->student_code)?></td></tr>
<tr><th>Nama</th><td><?=html_escape($row->name)?></td></tr>
<tr><th>Email</th><td><?=html_escape($row->email)?></td></tr>
<tr><th>Telepon</th><td><?=html_escape($row->phone)?></td></tr>
<tr><th>Kelas</th><td><?=html_escape($row->class_name)?></td></tr>
<tr><th>Terdaftar</th><td><?=html_escape($row->created_at)?></td></tr>
</table>
</div></div></div></section></div>