<script>
    model.masterModel = {
        id: 0,
        student_id: '',
        supervisor_id: '',
        consultation_date: '',
        start_time: '',
        end_time: '',
        topic: '',
        status: 'Pending',
        notes: '',
        created_at: ''
    };

    var material = {
        NAMA_CONSULTATION: 'Data Consultation',
        Recordmaterial: ko.mapping.fromJS(model.masterModel),
        Listmaterial: ko.observableArray([]),
        Mode: ko.observable(''),
        FilterText: ko.observable(''),
        FilterValue: ko.observable('consultations.topic'),
        SELECTSTUDENT: ko.observableArray([]),
        SELECTSUPERVISOR: ko.observableArray([]),

        SELECTFILTERVALUE: [
            { name: 'Student', value: 'students.name' },
            { name: 'Supervisor', value: 'supervisors.name' },
            { name: 'Topik', value: 'consultations.topic' },
            { name: 'Status', value: 'consultations.status' },
            { name: 'Tanggal', value: 'consultations.consultation_date' }
        ],

        STATUSLIST: [
            { name: 'Pending', value: 'Pending' },
            { name: 'Approved', value: 'Approved' },
            { name: 'Rejected', value: 'Rejected' },
            { name: 'Completed', value: 'Completed' }
        ]
    };

    material.filtermaterial = function() {
        material.grid.ajax.reload();
    };

    material.filterreset = function() {
        material.FilterText('');
        material.grid.ajax.reload(null, false);
    };

    material.back = function(tab) {
        material.Mode('');
        material.grid.ajax.reload(null, false);
        ko.mapping.fromJS(model.masterModel, material.Recordmaterial);
        model.activetab(tab);
    };

    material.loadDropdown = function() {
        var students = <?php echo json_encode(isset($students) ? $students : array()); ?>;
        var supervisors = <?php echo json_encode(isset($supervisors) ? $supervisors : array()); ?>;

        var studentArray = [];
        $.each(students, function(index, row) {
            studentArray.push({ id: String(row.id), text: row.student_code + ' - ' + row.name });
        });
        material.SELECTSTUDENT(studentArray);

        var supervisorArray = [];
        $.each(supervisors, function(index, row) {
            supervisorArray.push({ id: String(row.id), text: row.supervisor_code + ' - ' + row.name });
        });
        material.SELECTSUPERVISOR(supervisorArray);
    };

    material.selectdata = function(id) {
        model.Processing(true);
        ajaxPost("<?php echo site_url('consultations/getDataSelect') ?>", {
            id: id
        }, function(res) {
            console.log(res[0]);
            material.back(0);
            ko.mapping.fromJS(res[0], material.Recordmaterial);
            material.Recordmaterial.student_id(String(res[0].student_id));
            material.Recordmaterial.supervisor_id(String(res[0].supervisor_id));
            material.Mode("Update");
            model.Processing(false);
        });
    };

    material.checkConflict = function(callback) {
        var data = ko.mapping.toJS(material.Recordmaterial);
        ajaxPost("<?php echo base_url('consultations/checkConflict') ?>", data, function(res) {
            if (res && res.conflict) {
                swal("Jadwal Bentrok", res.message, "warning");
                callback(false);
            } else {
                callback(true);
            }
        });
    };

    material.save = function() {
        model.Processing(true);
        var val = material.Recordmaterial;
        swal({
            title: "Perhatian",
            text: "Anda akan simpan data ini?",
            type: "info", 
            className: 'animate_animated animate_fadeInUp',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes!",
            cancelButtonText: "No!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        }, function(isConfirm) {
            if (isConfirm) {
                if (material.Recordmaterial.student_id() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Student wajib dipilih!", "warning");
                    });
                } else if (material.Recordmaterial.supervisor_id() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Supervisor wajib dipilih!", "warning");
                    });
                } else if (material.Recordmaterial.consultation_date() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Tanggal konsultasi wajib diisi!", "warning");
                    });
                } else if (material.Recordmaterial.start_time() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Jam mulai wajib diisi!", "warning");
                    });
                } else if (material.Recordmaterial.end_time() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Jam selesai wajib diisi!", "warning");
                    });
                } else if (material.Recordmaterial.start_time() >= material.Recordmaterial.end_time()) {
                    setTimeout(function() {
                        swal("Peringatan!", "Jam mulai harus lebih kecil daripada jam selesai!", "warning");
                    });
                } else if (material.Recordmaterial.topic() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Topik konsultasi wajib diisi!", "warning");
                    });
                } else {
                    if (showLoaderOnConfirm = true) {
                        material.checkConflict(function(valid) {
                            if (!valid) return;

                            var url = "<?php echo base_url('consultations/save') ?>";

                            if (material.Mode() === 'Update')
                                url = "<?php echo base_url('consultations/update') ?>";

                            var currentMode = material.Mode();
                            ajaxPost(url, material.Recordmaterial,
                                function(res) {
                                    console.log(res.result);
                                    if (res.result == true || currentMode == "Update") {
                                        if (currentMode == "Update") {
                                            setTimeout(function() {
                                                swal({
                                                    title: "Good job!",
                                                    text: "Data Berhasil di ubah!",
                                                    icon: "success",
                                                });
                                            }, 2000);
                                        }
                                        if (res.result == true && currentMode != "Update") {
                                            setTimeout(function() {
                                                swal({
                                                    title: "Good job!",
                                                    text: "Data Berhasil di input!",
                                                    icon: "success",
                                                });
                                            }, 2000);
                                        }
                                        material.back(1);
                                    }
                                });
                        });
                    }
                }
            }
            model.Processing(false);
        });
        model.Processing(false);
    };

    material.updateStatus = function(id, status) {
        ajaxPost("<?php echo base_url('consultations/updateStatus') ?>", {
            id: id,
            status: status
        }, function(res) {
            if (res.result) {
                swal("Berhasil!", res.message, "success");
                material.grid.ajax.reload(null, false);
            } else {
                swal("Gagal!", res.message, "warning");
                material.grid.ajax.reload(null, false);
            }
        });
    };

    material.remove = function(id) {
        swal({
            title: "Are you sure?",
            text: "Delete this data?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes!",
            cancelButtonText: "No!",
            closeOnConfirm: false,
        }, function(isConfirm) {
            if (isConfirm) {
                ajaxPost("<?php echo base_url('consultations/delete') ?>", {
                    id: id
                }, function(res) {
                    if (res.result) {
                        material.back(1);
                        swal("Deleted!", "Data has been deleted successfully.", "success");
                    } else {
                        swal("Failed!", res.message, "warning");
                    }
                });
            }
        });
    };
</script>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modul Consultation</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row" data-bind="with: material">
                <div class="col-md-12">
                    <ul class="nav nav-tabs customtab" id="tabnavform">
                        <li class="nav-item"><a class="nav-link" href="#tabform" data-toggle="tab">Form</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#tablist" data-toggle="tab">List</a></li>
                    </ul>

                    <div class="content tab-content" id="tabnavform-content">
                        <div class="tab-pane active" id="tabform">
                            <div class="card card-primary">
                                <div class="card-body p-20 animated fadeIn m">
                                    <div class="row p-t-23 margMin">
                                        <div class="col-md-12 margMin">
                                            <div class="form-group">
                                                <button class="btn btn-sm btn-warning" data-bind="click:function(){back(1);}, visible: Mode() == 'Update'" data-toggle="tooltip" data-placement="top" data-original-title="Kembali">
                                                    <i class="fa fa-arrow-left"></i>
                                                </button>

                                                <button class="btn btn-sm btn-info" data-bind="click:save" data-toggle="tooltip" data-placement="top" data-original-title="Simpan">
                                                    <span data-bind="data-original-title:Mode"><i class="fa fa-save"></i></span>
                                                </button>

                                                <button class="btn btn-sm btn-danger" data-bind="click:function(){remove(Recordmaterial.id());}, visible: Mode() == 'Update'">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body" data-bind="with:Recordmaterial">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Student</label>
                                                    <select class="form-control" data-bind="options:$parent.SELECTSTUDENT, optionsText:'text', optionsValue:'id', value:student_id, optionsCaption:'-- Pilih Student --'"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Supervisor</label>
                                                    <select class="form-control" data-bind="options:$parent.SELECTSUPERVISOR, optionsText:'text', optionsValue:'id', value:supervisor_id, optionsCaption:'-- Pilih Supervisor --'"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Tanggal Konsultasi</label>
                                                    <input type="date" class="form-control" data-bind="value:consultation_date">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Jam Mulai</label>
                                                    <input type="time" class="form-control" data-bind="value:start_time">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Jam Selesai</label>
                                                    <input type="time" class="form-control" data-bind="value:end_time">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Topik Konsultasi</label>
                                                    <input type="text" class="form-control" data-bind="value:topic" placeholder="Masukkan topik konsultasi">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" data-bind="options:$parent.STATUSLIST, optionsText:'name', optionsValue:'value', value:status"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Catatan</label>
                                                    <textarea class="form-control" rows="4" data-bind="value:notes" placeholder="Catatan konsultasi"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane card card-white" id="tablist">
                            <div class="card-body p-20" data-bind="with:material">
                                <div class="row p-t-23">
                                    <div class="col-sm-4 col-md-2">
                                        <fieldset class="form-group">
                                            <select name="" data-bind="
                                                options: SELECTFILTERVALUE,
                                                optionsText: 'name',
                                                optionsValue: 'value',
                                                value: FilterValue"
                                                class="form-control" id="basicSelect">
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-sm-2 col-md-3">
                                        <div class="form-group">
                                            <input data-bind="value:FilterText, event: { keyup: function(data, event) {
                                                if (event.key === 'Enter') material.filtermaterial();
                                            }}" placeholder="Filter by data" class="form-control">
                                            <p>
                                                <small class="text-muted">Contoh: ketik <i>diskusi</i> lalu <b>Enter</b></small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-md-5 margFilter">
                                        <div class="form-group">
                                            <button class="btn btn-md btn-danger" data-bind="click:filterreset"><span class="fa fa-retweet"></span></button>
                                            <button class="btn btn-md btn-primary" data-bind="click:filtermaterial"><span class="fa fa-search"></span></button>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive m-t-40 animated fadeIn">
                                            <table id="myTable" width="100%" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Jam</th>
                                                        <th>Student</th>
                                                        <th>Supervisor</th>
                                                        <th>Topik</th>
                                                        <th>Status</th>
                                                        <th>Catatan</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        model.Processing(true);
         model.activetab(true);

        material.loadDropdown();

        material.grid = $("#myTable").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('consultations/getData') ?>",
                "type": "POST",
                "data": function(d) {
                    d['filtervalue'] = material.FilterValue();
                    d['filtertext']  = material.FilterText();
                    return d;
                },
                "dataSrc": function(json) {
                    json.recordsTotal    = json.RecordsTotal;
                    json.recordsFiltered = json.RecordsFiltered;

                    if (json.Data)
                        return json.Data;
                    else
                        return [];
                },
            },
            "searching": false,
            "columns": [
                { "data": "consultation_date" },
                {
                    "data": null,
                    "render": function(data) {
                        return (data.start_time || '') + ' - ' + (data.end_time || '');
                    }
                },
                { "data": "student_name" },
                { "data": "supervisor_name" },
                { "data": "topic" },
                {
                    "data": "status",
                    "render": function(data, type, full, meta) {
                        var badge = "secondary";
                        if (data === "Pending") badge = "warning";
                        else if (data === "Approved") badge = "success";
                        else if (data === "Rejected") badge = "danger";
                        else if (data === "Completed") badge = "primary";

                        var options = ["Pending", "Approved", "Rejected", "Completed"];
                        var optionHtml = "";
                        for (var i = 0; i < options.length; i++) {
                            optionHtml += "<option value='" + options[i] + "'" + (data === options[i] ? " selected" : "") + ">" + options[i] + "</option>";
                        }

                        return "<select class='form-control form-control-sm badge-" + badge + "' style='width:130px' onChange='material.updateStatus(\"" + full.id + "\", this.value)'>" + optionHtml + "</select>";
                    }
                },
                {
                    "data": "notes",
                    "render": function(data) {
                        if (!data) return "-";
                        var text = String(data);
                        return text.length > 30 ? text.substring(0, 30) + "..." : text;
                    }
                },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {
                        return "<a class='btn btn-sm btn-secondary' href='<?php echo site_url('consultations/detail') ?>/" + data + "'><i class='fa fa-eye'></i></a> &nbsp; " +
                               "<button class='btn btn-sm btn-info' onClick='material.selectdata(\"" + data + "\")'><i class='fa fa-edit'></i></button> &nbsp; " +
                               "<button class='btn btn-sm btn-danger' onClick='material.remove(\"" + data + "\")'><i class='fa fa-trash'></i></button>";
                    }
                }
            ],
        });
        model.Processing(false);
    });
</script>