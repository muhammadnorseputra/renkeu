<style>
    /* Tabs menyambung dengan panel di bawahnya */
    #tab-indikator .nav-link {
        border: 1px solid transparent;
        border-bottom: 0;
        margin-bottom: -1px;
    }

    #tab-indikator .nav-link:hover {
        border-color: #e5e5e5;
    }

    #tab-indikator .nav-link.active {
        background-color: #fff;
        border-color: #e5e5e5;
        /* hilangkan garis bawah hanya pada tab aktif */
        border-bottom-color: #fff;
    }

    #tab-indikator-content {
        border: 1px solid #e5e5e5;
        border-top: 0;
        background-color: #fff;
        padding: 1rem;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-bullseye mr-2"></i>Tabel Indikator</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <ul class="nav nav-tabs m-0" id="tab-indikator" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-indikator-2025" data-toggle="tab" href="#tab-indikator-2025-content" role="tab" aria-selected="true">Indikator 2025</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-indikator-2026" data-toggle="tab" href="#tab-indikator-2026-content" role="tab" aria-selected="false">Indikator 2026</a>
                        </li>
                    </ul>
                <div class="tab-content" id="tab-indikator-content">
                    <!-- CONTENT -->
                    <div class="tab-pane fade show active" id="tab-indikator-2025-content" role="tabpanel" aria-labelledby="tab-indikator-2025">
                        <form action="#" id="FilterForm" class="form-horizontal p-3 mb-3 bg-light border-0 border-bottom">
                            <div class="row">
                                <div class="col-md-3 border-right">
                                    <div class="from-group">
                                        <label for="periode">Filter Periode</label>
                                        <select name="periode" id="periode" class="form-control">
                                            <option value="">-- Pilih Periode --</option>
                                            <?php foreach (bulanIndo() as $key => $val): ?>
                                                <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="from-group">
                                        <label for="type">Filter Jenis</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">-- Pilih Referensi --</option>
                                            <?php if (in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])): ?>
                                            <option value="Tujuan">- Tujuan</option>
                                            <option value="Sasaran">- Sasaran</option>
                                            <?php endif; ?>
                                            <option value="Program">- Program</option>
                                            <option value="Kegiatan">- Kegiatan</option>
                                            <option value="SubKegiatan">- Sub Kegiatan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                            <table id="table-indikator" class="table jambo_table bulk_action dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Indikator</th>
                                        <th>Periode</th>
                                        <th>Jenis</th>
                                        <th>Tahun</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                    </div>
                    <!-- /CONTENT -->
                    <div class="tab-pane fade" id="tab-indikator-2026-content" role="tabpanel" aria-labelledby="tab-indikator-2026">
                        <form action="#" id="FilterForm2026" class="form-horizontal p-3 mb-3 bg-light border-0 border-bottom">
                            <div class="row">
                                <?php if (empty($is_super_admin)): ?>
                                <div class="col-md-3 border-right">
                                    <div class="from-group">
                                        <label for="fid_part_2026">Filter Bidang</label>
                                        <select name="fid_part" id="fid_part_2026" class="form-control" disabled>
                                            <?php foreach ($fid_parts as $key => $val): ?>
                                                <option value="<?php echo $key; ?>" <?php echo ($key == $session_part) ? 'selected' : ''; ?>><?php echo $val; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="form-text text-muted">Hanya menampilkan indikator bidang Anda.</small>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="col-md-3">
                                    <div class="from-group">
                                        <label for="fid_jenis_indikator_filter_2026">Filter Jenis Indikator</label>
                                        <select name="fid_jenis_indikator" id="fid_jenis_indikator_filter_2026" class="form-control">
                                            <option value="">-- Semua Jenis --</option>
                                            <?php foreach ($jenis_indikators as $j): ?>
                                                <option value="<?php echo $j->id; ?>" <?php echo ($j->is_aktif === 'N') ? 'disabled' : ''; ?>><?php echo $j->nama; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table id="table-indikator-2026" class="table jambo_table bulk_action dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Indikator</th>
                                    <th>Bidang / Bagian</th>
                                    <th>Jenis Indikator</th>
                                    <th>Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tambah Indikator 2026 -->
<div class="modal" id="modalTambah2026" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
            <div class="modal-header bg-primary text-white rounded-0">
                <h4 class="modal-title" id="modalTitleTambah2026"><i class="fa fa-plus mr-2"></i>Tambah Indikator 2026</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formTambah2026" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id_indikator_2026">
                    <div class="form-group">
                        <label for="nama_indikator_2026">Nama Indikator <span class="text-danger">*</span></label>
                        <textarea name="nama" id="nama_indikator_2026" class="form-control" rows="3" required placeholder="Masukkan nama indikator"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fid_jenis_indikator_2026">Jenis Indikator <span class="text-danger">*</span></label>
                        <select name="fid_jenis_indikator" id="fid_jenis_indikator_2026" class="form-control" required>
                            <option value="">-- Pilih Jenis Indikator --</option>
                            <?php foreach ($jenis_indikators as $j): ?>
                                <option value="<?php echo $j->id; ?>" <?php echo ($j->is_aktif === 'N') ? 'disabled' : ''; ?>><?php echo $j->nama; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label for="fid_part_form_2026">Bidang / Bagian <span class="text-danger">*</span></label>
                        <select name="fid_part" id="fid_part_form_2026" class="form-control" required>
                            <option value="">-- Pilih Bidang / Bagian --</option>
                            <?php foreach ($fid_parts as $key => $val): ?>
                                <option value="<?php echo $key; ?>" <?php echo (!$is_super_admin && $key == $session_part) ? 'selected' : ''; ?>><?php echo $val; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSimpan2026" class="btn btn-primary">
                        <i class="fa fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- The Modal -->
<div class="modal" id="modalDetail" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-0">

            <!-- Modal Header -->
            <div class="modal-header bg-success text-white rounded-0">
                <h4 class="modal-title">Detail Indikator</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let $form = $("form#FilterForm");
        let $periode = $form.find("select[name='periode']");
        let $type = $form.find("select[name='type']");

        // add button to datatable
        $.fn.dataTable.ext.buttons.add = {
            text: '<i class="fa fa-upload"></i> Tambah Indikator',
            action: function (e, dt, node, config) {
                window.location.href = `${_uri}/app/indikator/baru`
            },
            className: "btn btn-primary",
        };

        // === Inisialisasi DataTable ===
        var datatable = $("#table-indikator").DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            paging: true,
            ordering: true,
            info: true,
            searching: true,
            deferRender: true,
            responsive: true,
            datatype: "json",
            scrollCollapse: true,
            layout: {
                topStart: [
                    {
                        buttons: ["add"],
                    },
                    "pageLength",
                ],
                bottomStart: ["info"],
                bottomEnd: ["paging"],
            },
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            order: [],
            ajax: {
                url: `${_uri}/app/indikator/datatable`,
                type: "POST",
                data: function(d) {
                    d.periode = $periode.val();
                    d.type = $type.val();
                }
            },
            columns: [{
                    data: "no",
                    orderable: false,
                    width: "3%",
                    className: "text-center"
                },
                {
                    data: "nama",
                    orderable: true,
                    width: "60%"
                },
                {
                    data: "periode",
                    orderable: true,
                    className: "align-middle"
                },
                {
                    data: "nama_jenis_indikator",
                    orderable: false
                },
                {
                    data: "tahun",
                    orderable: false
                },
                {
                    data: "action",
                    width: "10%",
                    orderable: false,
                    searchable: false
                },
            ],
            language: {
                lengthMenu: "_MENU_ Data per halaman",
                zeroRecords: "Belum Ada Indikator",
                info: "Showing page _PAGE_ of _PAGES_",
                infoEmpty: "Belum Ada Indikator",
                infoFiltered: "(filtered from _MAX_ total records)",
                search: "Cari Indikator",
                paginate: {
                    previous: `<i class="fa fa-long-arrow-left"></i>`,
                    next: `<i class="fa fa-long-arrow-right"></i>`
                },
                emptyTable: "No matching records found, please filter this data"
            },

            // === Simpan nilai filter ke localStorage saat state disimpan ===
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_indikator_state', JSON.stringify({
                    dataTable: data,
                    filters: {
                        periode: $periode.val(),
                        type: $type.val()
                    }
                }));
            },

            // === Muat ulang nilai filter saat state di-restore ===
            stateLoadCallback: function(settings) {
                let saved = localStorage.getItem('DataTables_indikator_state');
                if (saved) {
                    saved = JSON.parse(saved);
                    // Restore nilai select ke UI
                    if (saved.filters) {
                        if (saved.filters.periode) $periode.val(saved.filters.periode);
                        if (saved.filters.type) $type.val(saved.filters.type);
                    }
                    return saved.dataTable;
                }
                return null;
            }
        });

        // === Event listener untuk perubahan filter ===
        $form.find("select[name='periode'], select[name='type']").on("change", function(e) {
            e.preventDefault();
            datatable.ajax.reload();
        });

        $("#table-indikator").on("click", "a.btn-delete", async function(e) {
            e.preventDefault();
            let $id = $(this).data('id');
            // ✅ Tambahkan konfirmasi sebelum submit
            const isConfirmed = await uiConfirm({
                title: "Hapus Indikator?",
                text: "Apakah Anda yakin ingin menghapus indikator tersebut ?",
                variant: "danger",
                ok: "Ya, Hapus",
            });
            if (!isConfirmed) return;

            // send
            try {
                // Kirim request DELETE ke server
                const response = await fetch(`${_uri}/app/indikator/delete/${$id}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || "Gagal menghapus data");
                }

                $.notify(result.message || "Data berhasil dihapus", {
                    timer: 800,
                    delay: 100,
                    type: "success",
                    onShow: function() {
                        $("#table-indikator").DataTable().ajax.reload(null, false);
                    },
                });

            } catch (error) {
                console.error("Error:", error);
                $.notify(error.message || "Terjadi kesalahan saat menghapus data.", {
                    timer: 2000,
                    delay: 100,
                    type: "danger",
                });
            }
        });

        $("#table-indikator").on("click", "a.btn-detail", async function(e) {
            e.preventDefault();
            let $id = $(this).data('id');
            let $modalDetail = $("#modalDetail");

            $modalDetail.modal("show");
            // Tampilkan placeholder loading sementara
            $modalDetail.find(".modal-body").html(`
                <div class="text-center my-4">
                    <div class="spinner-border text-success" role="status"></div>
                </div>
            `);
            // send
            try {
                // Kirim request DELETE ke server
                const response = await fetch(`${_uri}/app/indikator/detail/${$id}`, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || "Gagal membaca data");
                }

                $modalDetail.find(".modal-body").html(result.data.template || `
                    <div class="alert alert-info" role="alert">
                        Data indikator tidak tersedia.
                    </div>
                `);

            } catch (error) {
                console.error("Error:", error);
                $.notify(error.message || "Terjadi kesalahan saat membaca data.", {
                    timer: 2000,
                    delay: 100,
                    type: "danger",
                });
                $modalDetail.find(".modal-body").html(`
                    <div class="alert alert-danger" role="alert">
                        Gagal memuat data indikator. Silakan coba lagi.
                    </div>
                `);
            }
        })

        // === Inisialisasi DataTable Indikator 2026 ===
        // Tombol "Tambah Indikator" pada datatable 2026 -> buka modal
        $.fn.dataTable.ext.buttons.add2026 = {
            text: '<i class="fa fa-plus"></i> Tambah Indikator',
            action: function (e, dt, node, config) {
                $("#formTambah2026")[0].reset();
                $("#modalTambah2026").modal("show");
            },
            className: "btn btn-primary",
        };

        var datatable2026 = $("#table-indikator-2026").DataTable({
            stateSave: true,
            processing: true,
            serverSide: true,
            paging: true,
            ordering: true,
            info: true,
            searching: true,
            deferRender: true,
            responsive: true,
            datatype: "json",
            scrollCollapse: true,
            layout: {
                topStart: [
                    {
                        buttons: ["add2026"],
                    },
                    "pageLength",
                ],
                bottomStart: ["info"],
                bottomEnd: ["paging"],
            },
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            order: [],
            ajax: {
                url: `${_uri}/app/indikator/datatable_2026`,
                type: "POST",
                data: function(d) {
                    d.fid_part = $("#fid_part_2026").val();
                    d.fid_jenis_indikator = $("#fid_jenis_indikator_filter_2026").val();
                }
            },
            columns: [{
                    data: "no",
                    orderable: false,
                    width: "3%",
                    className: "text-center"
                },
                {
                    data: "nama",
                    orderable: true,
                    width: "60%"
                },
                {
                    data: "nama_bidang",
                    orderable: true,
                    className: "align-middle"
                },
                {
                    data: "nama_jenis_indikator",
                    orderable: true,
                    className: "align-middle"
                },
                {
                    data: "tahun",
                    orderable: false
                },
                {
                    data: "action",
                    width: "10%",
                    orderable: false,
                    searchable: false
                },
            ],
            language: {
                lengthMenu: "_MENU_ Data per halaman",
                zeroRecords: "Belum Ada Indikator",
                info: "Showing page _PAGE_ of _PAGES_",
                infoEmpty: "Belum Ada Indikator",
                infoFiltered: "(filtered from _MAX_ total records)",
                search: "Cari Indikator",
                paginate: {
                    previous: `<i class="fa fa-long-arrow-left"></i>`,
                    next: `<i class="fa fa-long-arrow-right"></i>`
                },
                emptyTable: "No matching records found, please filter this data"
            },

            // === Simpan nilai filter ke localStorage saat state disimpan ===
            stateSaveCallback: function(settings, data) {
                localStorage.setItem('DataTables_indikator_2026_state', JSON.stringify({
                    dataTable: data,
                    filters: {
                        fid_part: $("#fid_part_2026").val(),
                        fid_jenis_indikator: $("#fid_jenis_indikator_filter_2026").val()
                    }
                }));
            },

            // === Muat ulang nilai filter saat state di-restore ===
            stateLoadCallback: function(settings) {
                let saved = localStorage.getItem('DataTables_indikator_2026_state');
                if (saved) {
                    saved = JSON.parse(saved);
                    // Restore nilai select ke UI
                    if (saved.filters) {
                        if (saved.filters.fid_part) $("#fid_part_2026").val(saved.filters.fid_part);
                        if (saved.filters.fid_jenis_indikator) $("#fid_jenis_indikator_filter_2026").val(saved.filters.fid_jenis_indikator);
                    }
                    return saved.dataTable;
                }
                return null;
            }
        });

        // === Event listener untuk perubahan filter 2026 ===
        $("#fid_part_2026").on("change", function(e) {
            e.preventDefault();
            datatable2026.ajax.reload();
        });

        $("#fid_jenis_indikator_filter_2026").on("change", function(e) {
            e.preventDefault();
            datatable2026.ajax.reload();
        });

        // === Hapus Indikator 2026 ===
        $("#table-indikator-2026").on("click", "a.btn-delete-2026", async function(e) {
            e.preventDefault();
            let $id = $(this).data('id');

            const isConfirmed = await uiConfirm({
                title: "Hapus Indikator 2026?",
                text: "Apakah Anda yakin ingin menghapus indikator tersebut ?",
                variant: "danger",
                ok: "Ya, Hapus",
            });
            if (!isConfirmed) return;

            try {
                const response = await fetch(`${_uri}/app/indikator/delete/${$id}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || "Gagal menghapus data");
                }

                await uiAlert(result.message || "Data berhasil dihapus.", {
                    variant: "success",
                    ok: "Mengerti"
                });

                // Auto reload datatable tanpa refresh browser
                datatable2026.ajax.reload(null, false);

            } catch (error) {
                console.error("Error:", error);
                await uiAlert(error.message || "Terjadi kesalahan saat menghapus data.", {
                    variant: "danger",
                    ok: "Mengerti"
                });
            }
        });

        // === Modal Tambah Indikator 2026 ===
        let $modalTambah2026 = $("#modalTambah2026");
        let $formTambah2026 = $("#formTambah2026");
        let $btnSimpan2026 = $("#btnSimpan2026");

        // Reset modal ke mode tambah
        function resetModalTambah2026() {
            $formTambah2026[0].reset();
            $("#id_indikator_2026").val("");
            $("#modalTitleTambah2026").html('<i class="fa fa-plus mr-2"></i>Tambah Indikator 2026');
            $btnSimpan2026.html('<i class="fa fa-save mr-1"></i> Simpan');
        }

        // Tombol "Tambah Indikator" pada datatable 2026 -> buka modal
        $.fn.dataTable.ext.buttons.add2026 = {
            text: '<i class="fa fa-plus"></i> Tambah Indikator',
            action: function (e, dt, node, config) {
                resetModalTambah2026();
                $modalTambah2026.modal("show");
            },
            className: "btn btn-primary",
        };

        // Tombol Edit -> isi modal dari data row
        $("#table-indikator-2026").on("click", "a.btn-edit-2026", function(e) {
            e.preventDefault();
            resetModalTambah2026();
            $("#id_indikator_2026").val($(this).data('id'));
            $("#nama_indikator_2026").val($(this).data('nama'));
            $("#fid_part_form_2026").val($(this).data('fid_part'));
            $("#fid_jenis_indikator_2026").val($(this).data('fid_jenis_indikator'));
            $("#modalTitleTambah2026").html('<i class="fa fa-pencil mr-2"></i>Ubah Indikator 2026');
            $modalTambah2026.modal("show");
        });

        // Submit form via async/await
        $formTambah2026.on("submit", async function(e) {
            e.preventDefault();

            let $nama = $("#nama_indikator_2026");
            let $fidJenis = $("#fid_jenis_indikator_2026");
            let $fidPart = $("#fid_part_form_2026");

            // Validasi sederhana
            if ($nama.val().trim() === "") {
                await uiAlert("Nama indikator wajib diisi.", { variant: "warning", ok: "Mengerti" });
                $nama.focus();
                return;
            }
            if ($fidJenis.val() === "") {
                await uiAlert("Jenis Indikator wajib dipilih.", { variant: "warning", ok: "Mengerti" });
                $fidJenis.focus();
                return;
            }
            if ($fidPart.val() === "") {
                await uiAlert("Bidang / Bagian wajib dipilih.", { variant: "warning", ok: "Mengerti" });
                $fidPart.focus();
                return;
            }

            // Disable tombol saat proses
            $btnSimpan2026.prop("disabled", true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan...');

            try {
                let isEdit = $("#id_indikator_2026").val() !== "";
                const response = await fetch(`${_uri}/app/indikator/${isEdit ? "update_2026" : "simpan_2026"}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: new URLSearchParams(new FormData($formTambah2026[0])).toString()
                });

                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || "Gagal menyimpan data");
                }

                // Tutup modal & reset form
                $modalTambah2026.modal("hide");
                resetModalTambah2026();

                // Alert sukses
                await uiAlert(result.message || (isEdit ? "Data indikator berhasil diperbarui." : "Data indikator berhasil disimpan."), {
                    variant: "success",
                    ok: "Mengerti"
                });

                // Auto reload datatable tanpa refresh browser
                datatable2026.ajax.reload(null, false);

            } catch (error) {
                console.error("Error:", error);
                await uiAlert(error.message || "Terjadi kesalahan saat menyimpan data.", {
                    variant: "danger",
                    ok: "Mengerti"
                });
            } finally {
                $btnSimpan2026.prop("disabled", false).html('<i class="fa fa-save mr-1"></i> Simpan');
            }
        });

        // === Simpan & restore tab aktif ===
        $("a[data-toggle='tab']").on("shown.bs.tab", function(e) {
            localStorage.setItem('DataTables_indikator_tab', $(e.target).attr('href'));
        });

        // Restore tab aktif sebelum tab di-show oleh Bootstrap
        (function restoreTab() {
            let saved = localStorage.getItem('DataTables_indikator_tab');
            if (!saved) return;
            let $link = $("a[data-toggle='tab'][href='" + saved + "']");
            if ($link.length) $link.tab("show");
        })();
    });
</script>