document.addEventListener('DOMContentLoaded', () => {
    // --- Logika Sidebar Hamburger ---
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (hamburger) hamburger.addEventListener('click', () => sidebar.classList.add('show'));
    if (overlay) overlay.addEventListener('click', () => sidebar.classList.remove('show'));

    // --- Logika Litepicker ---
    const startDateEl = document.getElementById('start_date_picker');
    const endDateEl = document.getElementById('end_date_picker');

    if (startDateEl && endDateEl) {
        const startDatePicker = new Litepicker({
            element: startDateEl,
            singleMode: true,
            format: 'YYYY-MM-DD',
            placeholder: 'Pilih tanggal mulai...',
            dropdowns: { minYear: 2020, maxYear: null, months: true, years: true }
        });

        const endDatePicker = new Litepicker({
            element: endDateEl,
            singleMode: true,
            format: 'YYYY-MM-DD',
            placeholder: 'Pilih tanggal akhir...',
            dropdowns: { minYear: 2020, maxYear: null, months: true, years: true }
        });

        if (startDateEl.value) {
            startDatePicker.setDate(new Date(startDateEl.value));
        }
        if (endDateEl.value) {
            endDatePicker.setDate(new Date(endDateEl.value));
        }
    }

    // --- Logika Tombol Unduh ---
    const downloadBtn = document.getElementById('download-excel-btn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', () => {
            // Ambil nilai tanggal dari input
            const startDate = startDateEl.value;
            const endDate = endDateEl.value;

            // Buat URL
            let url = 'actions/download_report.php';
            const params = [];
            
            if (startDate) {
                params.push(`start_date=${startDate}`);
            }
            if (endDate) {
                params.push(`end_date=${endDate}`);
            }

            if (params.length > 0) {
                url += '?' + params.join('&');
            }
            
            // Buka URL di tab baru untuk memicu download
            window.open(url, '_blank');
        });
    }
});