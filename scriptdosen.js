function fetchSeminar() {
    fetch('fetch_seminar.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#seminar-table tbody');
            tableBody.innerHTML = '';
            data.forEach(item => {
                const row = `
                    <tr>
                        <td>${item.nama_mahasiswa}</td>
                        <td>${item.npm}</td>
                        <td>${item.nama_dosen}</td>
                        <td>${item.tanggal_seminar || 'Belum Dijadwalkan'}</td>
                        <td>${item.status}</td>
                        <td>
                            <button onclick="updateStatus(${item.id}, 'seminar', 'Diterima')">Terima</button>
                            <button onclick="updateStatus(${item.id}, 'seminar', 'Ditolak')">Tolak</button>
                            <button onclick="updateJadwal(${item.id}, 'seminar')">Ubah Jadwal</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        });
}

function fetchBimbingan() {
    fetch('fetch_bimbingan.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#bimbingan-table tbody');
            tableBody.innerHTML = '';
            data.forEach(item => {
                const row = `
                    <tr>
                        <td>${item.nama_mahasiswa}</td>
                        <td>${item.npm}</td>
                        <td>${item.nama_dosen}</td>
                        <td>${item.materi_bimbingan}</td>
                        <td>${item.tanggal_bimbingan || 'Belum Dijadwalkan'}</td>
                        <td>${item.status}</td>
                        <td>
                            <button onclick="updateStatus(${item.id}, 'bimbingan', 'Diterima')">Terima</button>
                            <button onclick="updateStatus(${item.id}, 'bimbingan', 'Ditolak')">Tolak</button>
                            <button onclick="updateJadwal(${item.id}, 'bimbingan')">Ubah Jadwal</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        });
}

function updateStatus(id, type, status) {
    fetch('update_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, type, status })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Status berhasil diubah ke ${status}`);
                if (type === 'seminar') fetchSeminar();
                else fetchBimbingan();
            } else {
                alert('Gagal mengubah status');
            }
        });
}

function updateJadwal(id, type) {
    const date = prompt('Masukkan tanggal baru (YYYY-MM-DD):');
    if (date) {
        fetch('update_jadwal.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, type, date })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Jadwal berhasil diubah');
                    if (type === 'seminar') fetchSeminar();
                    else fetchBimbingan();
                } else {
                    alert('Gagal mengubah jadwal');
                }
            });
    }
}

// Fetch data saat halaman dimuat
fetchSeminar();
fetchBimbingan();
