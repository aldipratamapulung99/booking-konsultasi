<script>
    // =========================================================
    // MASTER MODEL
    // =========================================================
    model.masterModel = {
        id: 0,
        supervisor_code: "",
        name: "",
        email: "",
        phone: "",
        specialization: "",
        created_at: ""
    };

    // =========================================================
    // MATERIAL
    // =========================================================
    var material = {
        NAMA_SUPERVISOR: "Data Supervisor",

        Recordmaterial: ko.mapping.fromJS(model.masterModel),

        Listmaterial: ko.observableArray([]),

        Mode: ko.observable(""),

        FilterText: ko.observable(""),

        FilterValue: ko.observable("name"),

        SELECTFILTERVALUE: [
            { name: "Kode Supervisor", value: "supervisor_code" },
            { name: "Nama Supervisor", value: "name" },
            { name: "Email", value: "email" },
            { name: "Telepon", value: "phone" },
            { name: "Spesialisasi", value: "specialization" }
        ]
    };

    // =========================================================
    // FILTER
    // =========================================================
    material.filtermaterial = function() {
        material.grid.ajax.reload();
    };

    material.filterreset = function() {
        material.FilterText('');
        material.grid.ajax.reload(null, false);
    };

    // =========================================================
    // BACK
    // =========================================================
    material.back = function(tab) {
        material.Mode('');
        material.grid.ajax.reload(null, false);
        ko.mapping.fromJS(model.masterModel, material.Recordmaterial);
        model.activetab(tab);
    };

    // =========================================================
    // SELECT DATA
    // =========================================================
    material.selectdata = function(id) {
        model.Processing(true);

        ajaxPost(
            "<?php echo site_url('supervisors/getDataSelect') ?>",
            { id: id },
            function(res) {
                console.log(res[0]);
                material.back(0);
                ko.mapping.fromJS(res[0], material.Recordmaterial);
                material.Mode("Update");
                model.Processing(false);
            }
        );
    };

    // =========================================================
    // SAVE
    // =========================================================
    material.save = function() {
        model.Processing(true);

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
            showLoaderOnConfirm: true
        }, function(isConfirm) {
            if (isConfirm) {
                if (material.Recordmaterial.name() == "") {
                    setTimeout(function() {
                        swal("Peringatan!", "Data Harap diisi Dengan Benar!", "warning");
                    });
                } else {
                    if (showLoaderOnConfirm = true) { // biarkan sesuai asli
                        var url = "<?php echo base_url('supervisors/save') ?>";

                        if (material.Mode() === 'Update') {
                            url = "<?php echo base_url('supervisors/update') ?>";
                        }

                        var currentMode = material.Mode();

                        ajaxPost(url, material.Recordmaterial, function(res) {
                            console.log(res.result);

                            if (res.result == true || currentMode == "Update") {
                                if (currentMode == "Update") {
                                    setTimeout(function() {
                                        swal({
                                            title: "Good job!",
                                            text: "Data Berhasil di ubah!",
                                            icon: "success"
                                        });
                                    }, 2000);
                                }

                                if (res.result == true && currentMode != "Update") {
                                    setTimeout(function() {
                                        swal({
                                            title: "Good job!",
                                            text: "Data Berhasil di input!",
                                            icon: "success"
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

    // =========================================================
    // DELETE
    // =========================================================
    material.remove = function(id) {
        swal({
            title: "Are you sure?",
            text: "Delete this data?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes!",
            cancelButtonText: "No!",
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm) {
                ajaxPost(
                    "<?php echo base_url('supervisors/delete') ?>",
                    { id: id },
                    function(res) {
                        if (res.result) {
                            material.back(1);
                            swal("Deleted!", "Data has been deleted successfully.", "success");
                        } else {
                            swal("Failed!", res.message, "warning");
                        }
                    }
                );
            }
        });
    };
</script>

<!-- =========================================================
     CONTENT WRAPPER
     ========================================================= -->
<div class="content-wrapper">

    <!-- =====================================================
         CONTENT HEADER
         ===================================================== -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modul Supervisor</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- =====================================================
         CONTENT
         ===================================================== -->
    <section class="content">
        <div class="container-fluid">
            <div class="row" data-bind="with: material">
                <div class="col-md-12">

                    <!-- NAV TAB -->
                    <ul class="nav nav-tabs customtab" id="tabnavform">
                        <li class="nav-item">
                            <a class="nav-link" href="#tabform" data-toggle="tab">Form</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#tablist" data-toggle="tab">List</a>
                        </li>
                    </ul>

                    <div class="content tab-content" id="tabnavform-content">

                        <!-- TAB FORM -->
                        <div class="tab-pane active" id="tabform">
                            <div class="card card-primary">
                                <div class="card-body p-20 animated fadeIn m">

                                    <div class="row p-t-23 margMin">
                                        <div class="col-md-12 margMin">
                                            <div class="form-group">

                                                <!-- BACK -->
                                                <button
                                                    class="btn btn-sm btn-warning"
                                                    data-bind="
                                                        click:function(){ back(1); },
                                                        visible: Mode() == 'Update'
                                                    "
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-original-title="Kembali"
                                                >
                                                    <i class="fa fa-arrow-left"></i>
                                                </button>

                                               
                                                <button
                                                    class="btn btn-sm btn-info"
                                                    data-bind="click:save"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    data-original-title="Simpan"
                                                >
                                                    <span data-bind="data-original-title:Mode">
                                                        <i class="fa fa-save"></i>
                                                    </span>
                                                </button>

                                                <!-- DELETE -->
                                                <button
                                                    class="btn btn-sm btn-danger"
                                                    data-bind="
                                                        click:function(){ remove(Recordmaterial.id()); },
                                                        visible: Mode() == 'Update'
                                                    "
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body" data-bind="with:Recordmaterial">
                                        <div class="row">

                                            <!-- KODE -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Kode Supervisor</label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        data-bind="value:supervisor_code"
                                                        placeholder="Kode Supervisor"
                                                    >
                                                </div>
                                            </div>

                                            <!-- NAMA -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Supervisor</label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        data-bind="value:name"
                                                        placeholder="Nama Supervisor"
                                                    >
                                                </div>
                                            </div>

                                            <!-- TELEPON -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Telepon</label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        data-bind="value:phone"
                                                        placeholder="Telepon"
                                                    >
                                                </div>
                                            </div>

                                            <!-- EMAIL -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input
                                                        type="email"
                                                        class="form-control"
                                                        data-bind="value:email"
                                                        placeholder="Email"
                                                    >
                                                </div>
                                            </div>

                                            <!-- SPESIALISASI -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Spesialisasi</label>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        data-bind="value:specialization"
                                                        placeholder="Spesialisasi"
                                                    >
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- TAB LIST -->
                        <div class="tab-pane card card-white" id="tablist">
                            <div class="card-body p-20">
                                <div class="row p-t-23">

                                    <!-- FILTER -->
                                    <div class="col-sm-4 col-md-2">
                                        <fieldset class="form-group">
                                            <select
                                                data-bind="
                                                    options: SELECTFILTERVALUE,
                                                    optionsText: 'name',
                                                    optionsValue: 'value',
                                                    value: FilterValue
                                                "
                                                class="form-control"
                                                id="basicSelect"
                                            ></select>
                                        </fieldset>
                                    </div>

                                    <!-- FILTER TEXT -->
                                    <div class="col-sm-2 col-md-3">
                                        <div class="form-group">
                                            <input
                                                data-bind="
                                                    value:FilterText,
                                                    event:{
                                                        keyup:function(data,event){
                                                            if(event.key === 'Enter'){
                                                                material.filtermaterial();
                                                            }
                                                        }
                                                    }
                                                "
                                                placeholder="Filter by data"
                                                class="form-control"
                                            >
                                            <p>
                                                <small class="text-muted">
                                                    Contoh: ketik <i>budi</i> lalu <b>Enter</b>
                                                </small>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- BUTTON FILTER -->
                                    <div class="col-sm-2 col-md-5 margFilter">
                                        <div class="form-group">
                                            <button class="btn btn-md btn-danger" data-bind="click:filterreset">
                                                <span class="fa fa-retweet"></span>
                                            </button>
                                            <button class="btn btn-md btn-primary" data-bind="click:filtermaterial">
                                                <span class="fa fa-search"></span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- TABLE -->
                                    <div class="col-md-12">
                                        <div class="table-responsive m-t-40 animated fadeIn">
                                            <table
                                                id="myTable"
                                                width="100%"
                                                class="table table-bordered table-striped"
                                            >
                                                <thead>
                                                    <tr>
                                                        <th>Kode</th>
                                                        <th>Nama</th>
                                                        <th>Telepon</th>
                                                        <th>Email</th>
                                                        <th>Spesialisasi</th>
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

<!-- =====================================================
     DATATABLE
     ===================================================== -->
<script>
    $(document).ready(function() {
        model.Processing(true);
        model.activetab(true);

        material.grid = $("#myTable").DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "ajax": {
                "url": "<?php echo base_url('supervisors/getData') ?>",
                "type": "POST",
                "data": function(d) {
                    d['filtervalue'] = material.FilterValue();
                    d['filtertext']  = material.FilterText();
                    return d;
                },
                "dataSrc": function(json) {
                    json.recordsTotal = json.RecordsTotal;
                    json.recordsFiltered = json.RecordsFiltered;
                    return json.Data || [];
                }
            },
            "columns": [
                { "data": "supervisor_code" },
                { "data": "name" },
                { "data": "phone" },
                { "data": "email" },
                { "data": "specialization" },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {
                        return "<a class='btn btn-sm btn-secondary' href='<?php echo site_url('supervisors/detail') ?>/" + data + "'><i class='fa fa-eye'></i></a> &nbsp; " +
                               "<button class='btn btn-sm btn-info' onClick='material.selectdata(\"" + data + "\")'><i class='fa fa-edit'></i></button> &nbsp; " +
                               "<button class='btn btn-sm btn-danger' onClick='material.remove(\"" + data + "\")'><i class='fa fa-trash'></i></button>";
                    }
                }
            ]
        });

        model.Processing(false);
    });
</script>