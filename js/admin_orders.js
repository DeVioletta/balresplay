document.addEventListener('DOMContentLoaded', () => {
    // Sidebar
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (hamburger) hamburger.addEventListener('click', () => sidebar.classList.add('show'));
    if (overlay) overlay.addEventListener('click', () => sidebar.classList.remove('show'));

    // Toggle Riwayat
    const toggleBtn = document.getElementById('toggle-view-btn');
    const activeGrid = document.getElementById('active-orders-grid');
    const historyGrid = document.getElementById('history-orders-grid');
    let isShowingHistory = false;
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            isShowingHistory = !isShowingHistory;
            if (isShowingHistory) {
                activeGrid.style.display = 'none';
                historyGrid.style.display = 'grid';
                toggleBtn.innerHTML = '<i class="fas fa-clipboard-list"></i> Lihat Pesanan Aktif';
            } else {
                activeGrid.style.display = 'grid';
                historyGrid.style.display = 'none';
                toggleBtn.innerHTML = '<i class="fas fa-history"></i> Lihat Riwayat';
            }
        });
    }

    // Filter Status
    const statusFilterAdmin = document.getElementById('status-filter-admin');
    if (statusFilterAdmin && activeGrid) {
        const allAdminItems = activeGrid.querySelectorAll('.order-card');
        if (allAdminItems.length > 0) {
            let noAdminResultsMessage = document.createElement('h3');
            noAdminResultsMessage.textContent = 'Tidak ada pesanan yang cocok dengan filter status.';
            noAdminResultsMessage.style.color = 'var(--text-muted)';
            noAdminResultsMessage.style.gridColumn = '1 / -1';
            noAdminResultsMessage.style.display = 'none';
            activeGrid.appendChild(noAdminResultsMessage);

            function filterAdminOrders() {
                const selectedStatus = statusFilterAdmin.value;
                let itemsFound = 0;
                allAdminItems.forEach(item => {
                    const itemStatus = item.dataset.status;
                    if (selectedStatus === 'all' || itemStatus === selectedStatus) {
                        item.style.display = 'block';
                        itemsFound++;
                    } else {
                        item.style.display = 'none';
                    }
                });
                noAdminResultsMessage.style.display = (itemsFound === 0) ? 'block' : 'none';
            }
            statusFilterAdmin.addEventListener('change', filterAdminOrders);
        }
    }

    // LOGIKA UPDATE STATUS
    const activeOrdersGrid = document.getElementById('active-orders-grid');
    if (activeOrdersGrid) {
        activeOrdersGrid.addEventListener('change', (e) => {
            if (e.target.classList.contains('status-select')) {
                const selectElement = e.target;
                const orderId = selectElement.dataset.orderId;
                const newStatus = selectElement.value;
                const card = selectElement.closest('.order-card');
                const originalStatus = card.dataset.status;
                
                let isConfirmed = true;
                if (newStatus === 'Selesai') {
                    isConfirmed = confirm(`Anda yakin ingin menyelesaikan Pesanan #${orderId}?`);
                } else if (newStatus === 'Dibatalkan') {
                    isConfirmed = confirm(`ANDA YAKIN ingin membatalkan Pesanan #${orderId}?`);
                } else if (newStatus !== originalStatus) {
                    isConfirmed = confirm(`Ubah status Pesanan #${orderId} ke ${newStatus}?`);
                }

                if (!isConfirmed) {
                    selectElement.value = originalStatus;
                    return; 
                }
                
                selectElement.disabled = true;
                const payload = { order_id: orderId, status: newStatus };

                fetch('actions/update_order_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Gagal: ' + data.message);
                        selectElement.value = originalStatus;
                        selectElement.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan koneksi.');
                    selectElement.value = originalStatus;
                    selectElement.disabled = false;
                });
            }
        });
    }
});