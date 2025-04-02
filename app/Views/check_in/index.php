// Handle konfirmasi check-in
$('#submitBtn').off('click').on('click', function() {
// Disable tombol submit
$(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

// Siapkan data untuk dikirim
var formData = {
id_member: $('#id_member').val(),
<?= csrf_token() ?>: '<?= csrf_hash() ?>'
};

// Kirim data ke server
$.ajax({
url: '<?= base_url('check-in/store') ?>',
type: 'POST',
data: formData,
dataType: 'json',
success: function(response) {
if (response.success) {
Swal.fire({
icon: 'success',
title: 'Berhasil!',
text: 'Check-in berhasil dilakukan',
showConfirmButton: false,
timer: 1500
}).then(() => {
location.reload();
});
} else {
Swal.fire({
icon: 'error',
title: 'Gagal!',
text: response.message
});
}
},
error: function() {
Swal.fire({
icon: 'error',
title: 'Gagal!',
text: 'Terjadi kesalahan saat melakukan check-in'
});
},
complete: function() {
// Enable tombol submit
$('#submitBtn').prop('disabled', false).html('Check-in');
}
});
});

// Handle konfirmasi hapus check-in
$('#confirmDelete').on('click', function() {
if (checkInToDelete) {
$.ajax({
url: '<?= base_url('check-in/delete') ?>/' + checkInToDelete,
type: 'POST',
data: {
<?= csrf_token() ?>: '<?= csrf_hash() ?>'
},
dataType: 'json',
success: function(response) {
if (response.success) {
Swal.fire({
icon: 'success',
title: 'Berhasil!',
text: 'Data check-in berhasil dihapus',
showConfirmButton: false,
timer: 1500
}).then(() => {
location.reload();
});
} else {
Swal.fire({
icon: 'error',
title: 'Gagal!',
text: response.message
});
}
},
error: function() {
Swal.fire({
icon: 'error',
title: 'Gagal!',
text: 'Terjadi kesalahan saat menghapus data check-in'
});
}
});
}
$('#deleteModal').modal('hide');
});