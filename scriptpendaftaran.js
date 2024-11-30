// Event listener untuk menampilkan input teks jika opsi 'Pembimbing Lain' dipilih untuk Pembimbing Utama
document.getElementById('otherPembimbing').addEventListener('change', function() {
    document.getElementById('inputPembimbingLain').style.display = 'block';
});

// Event listener untuk menyembunyikan input teks jika opsi lain dipilih untuk Pembimbing Utama
document.querySelectorAll('input[name="pembimbing"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        if (radio.value !== 'other') {
            document.getElementById('inputPembimbingLain').style.display = 'none';
        }
    });
});

// Event listener untuk menampilkan input teks jika opsi 'Pembimbing Lain' dipilih untuk Pembimbing Kedua
document.getElementById('otherPembimbingKedua').addEventListener('change', function() {
    document.getElementById('inputPembimbingKeduaLain').style.display = 'block';
});

// Event listener untuk menyembunyikan input teks jika opsi lain dipilih untuk Pembimbing Kedua
document.querySelectorAll('input[name="pembimbing_kedua"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        if (radio.value !== 'other') {
            document.getElementById('inputPembimbingKeduaLain').style.display = 'none';
        }
    });
});
