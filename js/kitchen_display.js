document.addEventListener('DOMContentLoaded', () => {
    // Logika Sidebar Hamburger
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (hamburger) hamburger.addEventListener('click', () => sidebar.classList.add('show'));
    if (overlay) overlay.addEventListener('click', () => sidebar.classList.remove('show'));
    
    // --- Logika Filter Status Dapur ---
    const statusFilterKitchen = document.getElementById('status-filter-kitchen');
    const kitchenGrid = document.getElementById('kitchen-grid');

    if (statusFilterKitchen && kitchenGrid) {
        const allKitchenItems = kitchenGrid.querySelectorAll('.kitchen-order-card');

        if (allKitchenItems.length > 0) {
            let noKitchenResultsMessage = document.createElement('div');
            noKitchenResultsMessage.className = 'no-orders-message';
            noKitchenResultsMessage.innerHTML = '<i class="fas fa-filter"></i>Tidak ada pesanan yang cocok dengan filter.';
            noKitchenResultsMessage.style.display = 'none';
            kitchenGrid.appendChild(noKitchenResultsMessage);

            function filterKitchenOrders() {
                const selectedStatus = statusFilterKitchen.value;
                let itemsFound = 0;

                allKitchenItems.forEach(item => {
                    const itemStatus = item.dataset.status;
                    if (selectedStatus === 'all' || itemStatus === selectedStatus) {
                        item.style.display = 'block'; 
                        itemsFound++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                noKitchenResultsMessage.style.display = (itemsFound === 0) ? 'block' : 'none';
            }

            statusFilterKitchen.addEventListener('change', filterKitchenOrders);
        }
    }

    // Logika KDS
    const grid = document.getElementById('kitchen-grid');
    if (grid) {
        grid.addEventListener('click', (e) => {
            const button = e.target.closest('.btn-kitchen');
            if (button) {
                e.preventDefault();
                const orderId = button.dataset.orderId;
                const nextStatus = button.dataset.nextStatus;
                button.disabled = true;
                button.textContent = 'Memperbarui...';
                const formData = new FormData();
                formData.append('order_id', orderId);
                formData.append('status', nextStatus);
                fetch('actions/update_kitchen_status.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const card = document.querySelector(`.kitchen-order-card[data-order-id="${orderId}"]`);
                        if (card) {
                            if (nextStatus === 'Sedang Dimasak') {
                                card.dataset.status = 'Sedang Dimasak';
                                card.querySelector('.order-details strong').textContent = 'Sedang Dimasak';
                                card.querySelector('.card-footer').innerHTML = `
                                    <button class="btn-kitchen btn-mark-ready" data-order-id="${orderId}" data-next-status="Siap Diantar">
                                        <i class="fas fa-check-double"></i> Tandai Siap Diantar
                                    </button>
                                `;
                            } else if (nextStatus === 'Siap Diantar') {
                                card.remove();
                                if (grid.children.length === 1) { // 1 karena "no results" message
                                    const noOrdersMsg = grid.querySelector('.no-orders-message');
                                    if(noOrdersMsg && noOrdersMsg.style.display === 'none') {
                                         // Jika tidak ada hasil filter, jangan tampilkan "Semua pesanan selesai"
                                    } else {
                                        // Cek jika *semua* item sudah hilang
                                        const remainingCards = grid.querySelectorAll('.kitchen-order-card[style*="display: block"]').length + grid.querySelectorAll('.kitchen-order-card:not([style])').length;
                                        if (remainingCards === 0) {
                                             grid.querySelector('.no-orders-message').innerHTML = '<i class="fas fa-clipboard-check"></i>Semua pesanan sudah selesai.';
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        alert('Gagal memperbarui status: ' + data.message);
                        button.disabled = false;
                        if(button.dataset.nextStatus === 'Sedang Dimasak') {
                            button.innerHTML = '<i class="fas fa-fire"></i> Mulai Masak';
                        } else {
                            button.innerHTML = '<i class="fas fa-check-double"></i> Tandai Siap Diantar';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan koneksi.');
                    button.disabled = false;
                    if(nextStatus === 'Sedang Dimasak') {
                        button.innerHTML = '<i class="fas fa-fire"></i> Mulai Masak';
                    } else {
                         button.innerHTML = '<i class="fas fa-check-double"></i> Tandai Siap Diantar';
                    }
                });
            }
        });
    }
});