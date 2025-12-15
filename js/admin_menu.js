document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (hamburger) hamburger.addEventListener('click', () => sidebar.classList.add('show'));
    if (overlay) overlay.addEventListener('click', () => sidebar.classList.remove('show'));

    // --- Logika Filter dan Search ---
    const categoryFilter = document.getElementById('category-filter');
    const searchInput = document.getElementById('menu-search-input');
    const menuGrid = document.querySelector('.admin-menu-grid');
    const allItems = document.querySelectorAll('.admin-menu-grid .menu-item');

    let noResultsMessage = document.createElement('p');
    noResultsMessage.classList.add('no-search-results');
    noResultsMessage.textContent = 'Tidak ada menu yang cocok dengan pencarian Anda.';
    if (menuGrid) menuGrid.appendChild(noResultsMessage);

    function filterMenuItems() {
        const selectedCategory = categoryFilter.value;
        const searchTerm = searchInput.value.toLowerCase().trim();
        let itemsFound = 0;

        allItems.forEach(item => {
            const itemCategory = item.dataset.category;
            const itemName = item.dataset.name.toLowerCase();

            const categoryMatch = (selectedCategory === 'all' || itemCategory === selectedCategory);
            const nameMatch = itemName.includes(searchTerm);

            if (categoryMatch && nameMatch) {
                item.style.display = 'flex';
                itemsFound++;
            } else {
                item.style.display = 'none';
            }
        });

        if (itemsFound === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }

    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterMenuItems);
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterMenuItems);
    }
    
    // --- Logika Tombol Hapus ---
    document.querySelectorAll('.btn-delete-menu').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            
            if (confirm(`Apakah Anda yakin ingin menghapus menu "${productName}"? Tindakan ini tidak dapat dibatalkan.`)) {
                fetch(`actions/delete_menu.php?id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Menu berhasil dihapus.');
                            // Hapus elemen dari DOM
                            this.closest('.menu-item').remove();
                        } else {
                            alert('Gagal menghapus menu: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan koneksi.');
                    });
            }
        });
    });

    // Logika untuk auto-hide message
    const messageElement = document.getElementById('auto-hide-message');
    if (messageElement) {
        setTimeout(() => {
            messageElement.style.opacity = '0'; 
            setTimeout(() => {
                messageElement.remove(); 
            }, 500); 
        }, 4000); 
    }
});