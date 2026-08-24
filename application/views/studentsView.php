<script>
    model.masterModel = {
        id: 0,
        student_code: '',
        name: '',
        email: '',
        phone: '',
        class_name: '',
        created_at: ''
    };

    var material = {
        NAMA_STUDENT: 'Data Student',
        Recordmaterial: ko.mapping.fromJS(model.masterModel),
        Listmaterial: ko.observableArray([]),
        Mode: ko.observable(''),
        FilterText: ko.observable(''),
        FilterValue: ko.observable('name'),

        SELECTFILTERVALUE: [
            { name: 'Kode Student', value: 'student_code' },
            { name: 'Nama', value: 'name' },
            { name: 'Email', value: 'email' },
            { name: 'Telepon', value: 'phone' },
            { name: 'Kelas', value: 'class_name' }
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

    material.selectdata = function(id) {
        model.Processing(true);
        ajaxPost("<?php echo site_url('students/getDataSelect') ?>", {
            id: id
        }, function(res) {
            console.log(res[0]);
            material.back(0);
            ko.mapping.fromJS(res[0], material.Recordmaterial);
            material.Mode("Update");
            model.Processing(false);
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
                if (material.Recordmaterial.name() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Nama Student wajib diisi!", "warning");
                    });
                } else {
                    if (showLoaderOnConfirm = true) {
                        var url = "<?php echo base_url('students/save') ?>";

                        if (material.Mode() === 'Update')
                            url = "<?php echo base_url('students/update') ?>";

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
                    }
                }
            }
            model.Processing(false);
        });
        model.Processing(false);
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
                ajaxPost("<?php echo base_url('students/delete') ?>", {
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
                    <h1>Data Students</h1>
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
                                                    <label>Kode Student</label>
                                                    <input type="text" class="form-control" data-bind="value:student_code" placeholder="Kode Student">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Student</label>
                                                    <input type="text" class="form-control" data-bind="value:name" placeholder="Nama Student">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" class="form-control" data-bind="value:email" placeholder="Email">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Telepon</label>
                                                    <input type="text" class="form-control" data-bind="value:phone" placeholder="Telepon">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Kelas</label>
                                                    <input type="text" class="form-control" data-bind="value:class_name" placeholder="Kelas">
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
                                                <small class="text-muted">Contoh: ketik <i>budi</i> lalu <b>Enter</b></small>
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
                                                        <th>Kode</th>
                                                        <th>Nama</th>
                                                        <th>Email</th>
                                                        <th>Telepon</th>
                                                        <th>Kelas</th>
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


        material.grid = $("#myTable").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo base_url('students/getData') ?>",
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
                { "data": "student_code" },
                { "data": "name" },
                { "data": "email" },
                { "data": "phone" },
                { "data": "class_name" },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {
                        return "<a class='btn btn-sm btn-secondary' href='<?php echo site_url('students/detail') ?>/" + data + "'><i class='fa fa-eye'></i></a> &nbsp; " +
                               "<button class='btn btn-sm btn-info' onClick='material.selectdata(\"" + data + "\")'><i class='fa fa-edit'></i></button> &nbsp; " +
                               "<button class='btn btn-sm btn-danger' onClick='material.remove(\"" + data + "\")'><i class='fa fa-trash'></i></button>";
                    }
                }
            ],
        });
        model.Processing(false);
    });
</script>