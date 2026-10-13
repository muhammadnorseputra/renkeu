/* ============================================================
   Pegawai Page Scripts
   ============================================================ */
$(document).ready(function () {
	// Klik tombol Mapping: isi form modal dari data-* baris
	$(".btn-mapping").on("click", function () {
		var jenis = $(this).data("jenis") || "PNS";
		var nama = $(this).data("nama") || "-";
		var jabatan = $(this).data("jabatan") || "-";
		var pangkat = $(this).data("pangkat") || "-";
		var golru = $(this).data("golru") || "-";
		var nip = $(this).data("nip") || "";

		$("#map_id").val(nip);
		$("#map_nama").text(nama);
		$("#map_jabatan").text(jabatan);
		$("#map_pangkat").text(pangkat);
		$("#map_golru").val(golru);
		$("#map_jenis").val(jenis);
		$("#map_nama_value").val(nama);
		$("#map_jabatan_value").val(jabatan);
		$("#map_pangkat_value").val(pangkat);
		$("#map_jenis_chip")
			.text(jenis)
			.css({
				background:
					jenis === "PNS"
						? "rgba(46, 125, 50, 0.12)"
						: "rgba(234, 153, 12, 0.12)",
				color: jenis === "PNS" ? "#256029" : "#9a5d00",
				"border-color":
					jenis === "PNS"
						? "rgba(46, 125, 50, 0.16)"
						: "rgba(234, 153, 12, 0.2)",
			});
		$("#map_bidang").val($(this).data("bidang") || "");
		$("#map_sync_at").text($(this).data("created-at") || "-");
		$("#map_sync_by").text($(this).data("created-by") || "-");
		$("#map_updated_at").text($(this).data("updated-at") || "-");
		$("#map_updated_by").text($(this).data("updated-by") || "-");

		// Muat list indikator berdasarkan bidang
		loadIndikatorByBidang($(this).data("bidang") || "");

		$("#modalMapping").modal("show");
	});

	// Ubah bidang -> muat ulang list indikator
	$("#map_bidang").on("change", function () {
		loadIndikatorByBidang($(this).val());
	});

	// Load list indikator berdasarkan bidang (async await)
	async function loadIndikatorByBidang(fidPart) {
		const $panel = $("#mappingIndikatorPanel");
		const $body = $("#mappingIndikatorBody");
		const $count = $("#mappingIndikatorCount");

		$body.empty();
		$count.text("0");

		if (!fidPart) {
			$panel.hide();
			return;
		}

		// Tampilkan efek loading
		$panel.show();
		$body.html(
			'<div class="mapping-indikator-loading"><i class="fa fa-spinner fa-spin"></i> Memuat indikator...</div>'
		);

		try {
			const res = await $.ajax({
				url: _uri + "/app/pegawai/get_indikator_by_bidang",
				type: "post",
				data: { fid_part: fidPart, nip: $("#map_id").val() },
				dataType: "json",
			});

			if (!res.status) throw new Error(res.pesan || "Gagal memuat indikator.");

			if (!res.data || res.data.length === 0) {
				$body.html(
					'<div class="mapping-indikator-empty"><i class="fa fa-info-circle"></i> Tidak ada indikator untuk bidang ini.</div>'
				);
				return;
			}

			$count.text(res.data.length);
			const items = res.data
				.map((item, idx) => {
					const sudahAda = !!item.pegawai_indikator_id;
					// Dua tombol: Pilih (disabled bila sudah dipilih) + Hapus (hanya kalau sudah dipilih)
					const btnPilih = sudahAda
						? `<button type="button" class="btn-indikator-aksi" data-aksi="simpan" data-fid-indikator="${item.id}" title="Sudah dipilih" disabled><i class="fa fa-check"></i> Pilih</button>`
						: `<button type="button" class="btn-indikator-aksi" data-aksi="simpan" data-fid-indikator="${item.id}" title="Pilih indikator"><i class="fa fa-check"></i> Pilih</button>`;
					const btnHapus = sudahAda
						? `<button type="button" class="btn-indikator-aksi is-picked" data-aksi="hapus" data-fid-indikator="${item.id}" title="Batalkan pilihan"><i class="fa fa-times"></i> Hapus</button>`
						: "";
					return `
					<div class="mapping-indikator-item">
						<span class="indikator-no">${idx + 1}</span>
						<span class="indikator-nama">${item.nama} &bull; <span class="indikator-jenis ${item.color || ""}">${item.nama_jenis_indikator || "-"}</span></span>
						<div class="indikator-aksi">
							${btnPilih}
							${btnHapus}
						</div>
					</div>`;
				})
				.join("");
			$body.html(items);
		} catch (err) {
			$body.html(
				'<div class="mapping-indikator-error"><i class="fa fa-exclamation-triangle"></i> Gagal memuat indikator. Silakan coba lagi.</div>'
			);
		}
	}

	// Toast UI (success / warn / error)
	function pegawaiToast(pesan, tipe) {
		const ico =
			tipe === "success"
				? "fa-check-circle"
				: tipe === "error"
					? "fa-times-circle"
					: "fa-exclamation-triangle";
		const $el = $(
			`<div class="pg-toast pg-toast--${tipe || "warn"}">` +
				`<i class="fa ${ico}"></i><span>${pesan}</span>` +
				`<button type="button" class="pg-toast-x"><i class="fa fa-times"></i></button>` +
				`</div>`
		);
		$("body").append($el);
		requestAnimationFrame(() => $el.addClass("pg-toast--in"));
		const t = setTimeout(() => pegawaiToastClose($el), 3200);
		$el.find(".pg-toast-x").on("click", () => {
			clearTimeout(t);
			pegawaiToastClose($el);
		});
	}
	function pegawaiToastClose($el) {
		$el.removeClass("pg-toast--in").addClass("pg-toast--out");
		setTimeout(() => $el.remove(), 300);
	}

	// Aksi indikator: Pilih (simpan) / Batal (hapus) — async await
	$("#mappingIndikatorBody").on("click", ".btn-indikator-aksi", async function () {
		const $btn = $(this);
		const $icon = $btn.find("i");
		const aksi = $btn.data("aksi");
		const fid = $btn.data("fid-indikator");
		const nip = $("#map_id").val();

		if (!fid || !nip) {
			pegawaiToast("NIP atau indikator tidak ditemukan.", "warn");
			return;
		}

		const isHapus = aksi === "hapus";
		if (
			isHapus &&
			!(
				await uiConfirm({
					title: "Batal Pilih?",
					text: "Yakin ingin membatalkan pilihan indikator ini?",
					variant: "danger",
					ok: "Ya, Batal",
				})
			)
		) {
			return;
		}

		$btn.prop("disabled", true);
		$icon.removeClass("fa-check fa-times").addClass("fa-spinner fa-spin");
		let berhasil = false;

		try {
			const res = await $.ajax({
				url: _uri + "/app/pegawai/" + (isHapus ? "hapus_indikator_pegawai" : "simpan_indikator_pegawai"),
				type: "post",
				data: { nip: nip, fid_indikator: fid },
				dataType: "json",
			});

			if (res.status) {
				berhasil = true;
				pegawaiToast(res.pesan || (isHapus ? "Pilihan indikator dibatalkan." : "Indikator berhasil dipilih."), "success");
				const $grup = $btn.closest(".indikator-aksi");
				if (isHapus) {
					// Batal -> tombol Hapus hilang, tombol Pilih aktif kembali
					$btn.remove();
					$grup.find('[data-aksi="simpan"]').prop("disabled", false);
				} else {
					// Pilih -> tombol Pilih nonaktif (cegah klik ulang), tombol Hapus merah muncul
					$btn.prop("disabled", true).attr("title", "Sudah dipilih");
					$icon.removeClass("fa-spinner fa-spin").addClass("fa-check");
					if (!$grup.children('[data-aksi="hapus"]').length) {
						$grup.append(
							'<button type="button" class="btn-indikator-aksi is-picked" data-aksi="hapus" data-fid-indikator="' +
								fid +
								'" title="Batalkan pilihan"><i class="fa fa-times"></i> Hapus</button>'
						);
					}
				}
			} else {
				pegawaiToast(res.pesan || "Gagal menyimpan indikator.", "warn");
			}
		} catch (err) {
			pegawaiToast("Terjadi kesalahan. Silakan coba lagi.", "error");
		} finally {
			// Hanya aktifkan lagi kalau gagal; kalau sukses state-nya sudah diatur di atas
			if (!berhasil) {
				$btn.prop("disabled", false);
				$icon.removeClass("fa-spinner fa-spin").addClass(isHapus ? "fa-times" : "fa-check");
			}
		}
	});

	// Klik Batal Sync: hapus data pegawai dari tabel pegawai
	$("#btnBatalSync").on("click", async function () {
		var nip = $("#map_id").val();
		if (!nip) {
			alert("NIP tidak ditemukan.");
			return;
		}
		if (
			!(await uiConfirm({
				title: "Hapus Mapping?",
				text:
					"Yakin ingin menghapus data pegawai NIP " + nip + " dari mapping?",
				variant: "danger",
				ok: "Ya, Hapus",
			}))
		) {
			return;
		}
		var $btn = $(this);
		var $icon = $btn.find("i");
		var $label = $btn.find("span");
		$btn.prop("disabled", true);
		$icon.removeClass("fa-trash").addClass("fa-spinner fa-spin");
		$label.text("Menghapus...");

		$.ajax({
			url: _uri + "/app/pegawai/delete_mapping",
			type: "post",
			data: { nip: nip },
			dataType: "json",
			success: function (res) {
				if (res.status) {
					$("#modalMapping").modal("hide");
					location.reload();
				} else {
					alert(res.pesan);
				}
			},
			error: function () {
				alert("Terjadi kesalahan. Silakan coba lagi.");
			},
			complete: function () {
				$btn.prop("disabled", false);
				$icon.removeClass("fa-spinner fa-spin").addClass("fa-trash");
				$label.text("Batal Sync");
			},
		});
	});

	// Submit form mapping
	$("#formMapping").on("submit", async function (e) {
		e.preventDefault();
		var $btn = $("#btnSync");
		var $icon = $btn.find("i");
		var $label = $btn.find("span");
		$btn.prop("disabled", true);
		$icon.removeClass("fa-refresh").addClass("fa-spinner fa-spin");
		$label.text("Menyimpan...");

		try {
			const res = await $.ajax({
				url: _uri + "/app/pegawai/save_mapping",
				type: "post",
				data: {
					nip: $("#map_id").val(),
					nama: $("#map_nama_value").val(),
					jabatan: $("#map_jabatan_value").val(),
					pangkat: $("#map_pangkat_value").val(),
					jenis: $("#map_jenis").val(),
					bidang: $("#map_bidang").val(),
				},
				dataType: "json",
			});

			if (res.status) {
				$("#modalMapping").modal("hide");
				location.reload();
			} else {
				alert(res.pesan);
			}
		} catch (err) {
			alert("Terjadi kesalahan. Silakan coba lagi.");
		} finally {
			$btn.prop("disabled", false);
			$icon.removeClass("fa-spinner fa-spin").addClass("fa-refresh");
			$label.text("Sync");
		}
	});
});
