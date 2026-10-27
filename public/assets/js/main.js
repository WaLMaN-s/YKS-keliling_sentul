/**
 * YKS - Keliling Sentul (Main JavaScript)
 */

document.addEventListener('DOMContentLoaded', () => {
    // FAQ Accordion
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        if (question) {
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                faqItems.forEach(f => f.classList.remove('active'));
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        }
    });

    // Booking Calculation
    const qtyInput = document.getElementById('jumlahPeserta');
    const pricePerPerson = document.getElementById('hargaPerOrang');
    const totalDisplay = document.getElementById('totalHargaDisplay');
    const totalInput = document.getElementById('totalHargaInput');

    if (qtyInput && pricePerPerson && totalDisplay) {
        const price = parseInt(pricePerPerson.dataset.harga, 10) || 0;
        const calculateTotal = () => {
            let qty = parseInt(qtyInput.value, 10);
            if (isNaN(qty) || qty < 1) qty = 1;
            const total = qty * price;
            totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
            if (totalInput) totalInput.value = total;
        };

        qtyInput.addEventListener('input', calculateTotal);
        qtyInput.addEventListener('change', calculateTotal);

        // Plus and Minus buttons if available
        const btnMinus = document.getElementById('btnMinus');
        const btnPlus = document.getElementById('btnPlus');
        if (btnMinus && btnPlus) {
            btnMinus.addEventListener('click', () => {
                let val = parseInt(qtyInput.value, 10) || 1;
                if (val > 1) {
                    qtyInput.value = val - 1;
                    calculateTotal();
                }
            });
            btnPlus.addEventListener('click', () => {
                let val = parseInt(qtyInput.value, 10) || 1;
                qtyInput.value = val + 1;
                calculateTotal();
            });
        }
    }

    // File Upload Preview
    const fileInput = document.getElementById('buktiBayarInput');
    const previewBox = document.getElementById('previewBox');
    if (fileInput && previewBox) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewBox.innerHTML = `<img src="${e.target.result}" alt="Preview Bukti" style="max-height: 200px; border-radius: 8px; margin-top: 10px; border: 1px solid #cbd5e1;">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

// Mobile Navigation Drawer Toggle
function toggleMobileNav() {
    const drawer = document.getElementById('mobileDrawer');
    if (drawer) {
        drawer.classList.toggle('open');
        document.body.style.overflow = drawer.classList.contains('open') ? 'hidden' : '';
    }
}

// User Profile Dropdown Toggle
function toggleUserDropdown() {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

// Close dropdown if clicking outside
window.addEventListener('click', (e) => {
    const btn = document.getElementById('userBtn');
    const dropdown = document.getElementById('userDropdown');
    if (btn && dropdown && !btn.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});

// Package Category Filter
function filterCategory(slug, btnElement) {
    // Update active button
    const pills = document.querySelectorAll('.filter-pill');
    pills.forEach(p => p.classList.remove('active'));
    if (btnElement) btnElement.classList.add('active');

    // Filter cards
    const cards = document.querySelectorAll('.tour-card-item');
    cards.forEach(card => {
        const cat = card.dataset.category;
        if (slug === 'all' || cat === slug) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
