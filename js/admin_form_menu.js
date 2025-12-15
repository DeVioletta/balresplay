document.addEventListener('DOMContentLoaded', () => {
    // Ambil variabel konfigurasi dari global window object
    const isDapur = window.config.isDapur;
    const initialVariantIndex = window.config.initialVariantIndex;
    const productId = window.config.productId;

    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (hamburger) hamburger.addEventListener('click', () => sidebar.classList.add('show'));
    if (overlay) overlay.addEventListener('click', () => sidebar.classList.remove('show'));

    const variantsContainer = document.getElementById('variants-container');
    const addVariantBtn = document.getElementById('add-variant-btn');
    
    const typeRadios = document.querySelectorAll('input[name="ui_product_type"]');
    
    typeRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (isDapur) return;

            if (e.target.value === 'simple') {
                // Jika pindah ke Satuan
                const rows = variantsContainer.querySelectorAll('.variant-row');
                const confirmMsg = "Mengubah ke tipe 'Satuan' akan menghapus semua varian tambahan. Lanjutkan?";
                
                if (rows.length > 1) {
                    if (!confirm(confirmMsg)) {
                        document.querySelector('input[value="variable"]').checked = true;
                        return;
                    }
                }

                while (variantsContainer.children.length > 1) {
                    variantsContainer.lastChild.remove();
                }

                const firstRowName = variantsContainer.querySelector('input[name*="[name]"]');
                if (firstRowName) firstRowName.value = '';

                variantsContainer.classList.add('mode-simple');
                addVariantBtn.style.display = 'none';

            } else {
                // Jika pindah ke Varian
                variantsContainer.classList.remove('mode-simple');
                addVariantBtn.style.display = 'inline-flex';
            }
        });
    });

    let variantIndex = initialVariantIndex;

    addVariantBtn.addEventListener('click', () => {
        let maxIndex = -1;
        variantsContainer.querySelectorAll('.variant-row').forEach(row => {
           const inputName = row.querySelector('input[name^="variants"]');
           if(inputName) {
               const matches = inputName.name.match(/\[(\d+)\]/);
               if(matches && matches[1]) {
                   const idx = parseInt(matches[1]);
                   if(idx > maxIndex) maxIndex = idx;
               }
           }
        });
        variantIndex = maxIndex + 1;

        const newRow = document.createElement('div');
        newRow.classList.add('variant-row');
        
        newRow.innerHTML = `
            <input type="text" name="variants[${variantIndex}][name]" class="variant-name-input" placeholder="Nama Varian (cth: Hot / Ice)" ${isDapur ? 'disabled' : ''}>
            <input type="number" name="variants[${variantIndex}][price]" placeholder="Harga" required ${isDapur ? 'disabled' : ''}>
            <div class="variant-availability">
                <input type="checkbox" id="available-${variantIndex}" name="variants[${variantIndex}][is_available]" value="1" checked>
                <label for="available-${variantIndex}">Tersedia</label>
            </div>
            <button type="button" class="btn btn-delete-variant" ${isDapur ? 'disabled' : ''}>
                <i class="fas fa-trash"></i>
            </button>
        `;
        variantsContainer.appendChild(newRow);
    });

    variantsContainer.addEventListener('click', (e) => {
        if (e.target.closest('.btn-delete-variant')) {
            if (variantsContainer.children.length > 1) {
                e.target.closest('.variant-row').remove();
            } else {
                const lastRow = variantsContainer.querySelector('.variant-row');
                const nameInput = lastRow.querySelector('input[type="text"]');
                if(nameInput) nameInput.value = '';
                
                const priceInput = lastRow.querySelector('input[type="number"]');
                if(priceInput) priceInput.value = '';
                
                lastRow.querySelector('input[type="checkbox"]').checked = true;
                alert("Minimal harus ada satu harga.");
            }
        }
    });

    const imageInput = document.getElementById('product_image');
    const imagePreview = document.getElementById('image_preview');
    const originalImageSrc = imagePreview.src;
    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => { imagePreview.src = event.target.result; };
            reader.readAsDataURL(file);
        } else {
            imagePreview.src = originalImageSrc;
        }
    });

    // Validasi Form
    const menuForm = document.getElementById('menu-form');
    const errorMessage = document.getElementById('form-error-message');
    
    menuForm.addEventListener('submit', (e) => {
        if (isDapur) { return; }
        
        errorMessage.style.display = 'none';
        errorMessage.innerHTML = '';
        let errors = [];

        const requiredFields = menuForm.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (field.disabled || field.offsetParent === null) return;

            if (field.value.trim() === '') {
                let fieldName = field.placeholder || field.name;
                const labelElement = menuForm.querySelector(`label[for="${field.id}"]`);
                if (labelElement) fieldName = labelElement.textContent;
                
                if (fieldName.includes('Nama Menu')) errors.push('- Nama Menu wajib diisi.');
                else if (fieldName.includes('Harga')) errors.push('- Harga wajib diisi.');
                else if (!errors.includes(`- ${fieldName} wajib diisi.`)) errors.push(`- ${fieldName} wajib diisi.`);
            }
        });

        const category = document.getElementById('product_category').value;
        const newCategory = document.getElementById('new_category').value.trim();
        if (category === '' && newCategory === '') {
            errors.push('- Kategori wajib dipilih atau diisi.');
        }

        if (errors.length > 0) {
            e.preventDefault(); 
            errorMessage.innerHTML = '<strong>Validasi Gagal:</strong><br>' + errors.join('<br>');
            errorMessage.style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            const isSimpleMode = document.querySelector('input[name="ui_product_type"][value="simple"]').checked;
            if (isSimpleMode) {
                const variantNameInputs = variantsContainer.querySelectorAll('.variant-name-input');
                variantNameInputs.forEach(input => input.value = '');
            }
        }
    });

    const btnDeleteForm = document.getElementById('btn-delete-product-form');
    if (btnDeleteForm) {
        btnDeleteForm.addEventListener('click', () => {
            if (confirm('Apakah Anda yakin ingin menghapus menu ini?')) {
                window.location.href = `actions/delete_menu.php?id=${productId}`; 
            }
        });
    }
});