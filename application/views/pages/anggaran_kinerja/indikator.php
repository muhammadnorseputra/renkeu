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
                <div class="row">
                    <!-- CONTENT -->
                    <div class="col-sm-12">
                        <form action="#" id="FilterForm" class="form-horizontal border p-3 mb-3 mx-2 bg-light">
                            <div class="row">
                                <div class="col-md-3 border-right">
                                    <div class="from-group">
                                        <label for="periode">Filter Periode</label>
                                        <select name="periode" id="periode" class="form-control">
                                            <option value="">-- Pilih Periode --</option>
                                            <?php foreach (bulanIndo() as $key => $val): ?>
                                                <option value="<?= $key; ?>"><?= $val; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="from-group">
                                        <label for="type">Filter Jenis</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">-- Pilih Referensi --</option>
                                            <?php if(in_array($this->session->userdata('role'), ['ADMIN', 'SUPER_ADMIN'])): ?>
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
                </div>
            </div>
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
            const isConfirmed = confirm("Apakah Anda yakin ingin menghapus indikator tersebut ?");
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
    });
</script>