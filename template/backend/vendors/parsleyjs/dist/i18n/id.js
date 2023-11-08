// Validation errors messages for Parsley
// Load this after Parsley

Parsley.addMessages('id', {
  defaultMessage: "Tidak valid",
  type: {
    email:        "Email tidak valid",
    url:          "Url tidak valid",
    number:       "Nomor tidak valid",
    integer:      "Integer tidak valid",
    digits:       "Harus berupa digit",
    alphanum:     "Harus berupa alphanumeric"
  },
  notblank:       "Bagian ini tidak boleh kosong !",
  required:       "Bagian ini tidak boleh kosong !",
  pattern:        "Tidak valid",
  min:            "Harus lebih besar atau sama dengan %s.",
  max:            "Harus lebih kecil atau sama dengan %s.",
  range:          "Harus dalam rentang %s dan %s.",
  minlength:      "Terlalu pendek, minimal %s karakter atau lebih.",
  maxlength:      "Terlalu panjang, maksimal %s karakter atau kurang.",
  length:         "Panjang karakter harus dalam rentang %s dan %s",
  mincheck:       "Pilih minimal %s pilihan",
  maxcheck:       "Pilih maksimal %s pilihan",
  check:          "Pilih antar %s dan %s pilihan",
  equalto:        "Harus sama dengan bagian %s"
});

Parsley.setLocale('id');
