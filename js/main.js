
function toggleNightMode() {
    const body = document.body;
    const btn = document.getElementById('night-toggle-btn');
    
    body.classList.toggle('night-mode');
    
    const isNight = body.classList.contains('night-mode');
    
    if (btn) {
        btn.innerHTML = isNight 
            ? 'الوضع النهاري' 
            : 'الوضع الليلي';
    }
    
    localStorage.setItem('nightMode', isNight ? 'on' : 'off');
}

function loadNightModePreference() {
    const saved = localStorage.getItem('nightMode');
    if (saved === 'on') {
        document.body.classList.add('night-mode');
        const btn = document.getElementById('night-toggle-btn');
        if (btn) {
            btn.innerHTML = ' الوضع النهاري';
        }
    }
}

function filterGallery() {
    const searchText = document.getElementById('search-input')?.value?.toLowerCase() || '';
    const filterValue = document.getElementById('filter-select')?.value || 'all';
    const cards = document.querySelectorAll('.place-card');
    let visible = 0;
    
    cards.forEach(card => {
        const name = card.dataset.name?.toLowerCase() || '';
        const region = card.dataset.region?.toLowerCase() || '';
        const classification = card.dataset.classification || '';
        const desc = card.dataset.desc?.toLowerCase() || '';
        
        const matchesSearch = !searchText || 
            name.includes(searchText) || 
            region.includes(searchText) || 
            desc.includes(searchText);
        
        const matchesFilter = filterValue === 'all' || classification === filterValue;
        
        if (matchesSearch && matchesFilter) {
            card.style.display = 'block';
            card.style.animation = 'fadeIn 0.3s ease';
            visible++;
        } else {
            card.style.display = 'none';
        }
    });
    
    const counter = document.getElementById('results-count');
    if (counter) {
        counter.textContent = `عدد النتائج: ${visible}`;
    }
    
    const noResults = document.getElementById('no-results');
    if (noResults) {
        noResults.style.display = visible === 0 ? 'block' : 'none';
    }
}

function openImage(src) {
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.9); z-index: 9999;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
    `;
    
    const img = document.createElement('img');
    img.src = src;
    img.style.cssText = `
        max-width: 90vw; max-height: 90vh;
        border-radius: 8px; box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    `;
    
    overlay.appendChild(img);
    overlay.onclick = () => document.body.removeChild(overlay);
    document.body.appendChild(overlay);
}

function initScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.info-card, .place-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadNightModePreference();
    
    const toggleBtn = document.getElementById('night-toggle-btn');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleNightMode);
    }
    
    const searchInput = document.getElementById('search-input');
    const filterSelect = document.getElementById('filter-select');
    
    if (searchInput) {
        searchInput.addEventListener('input', filterGallery);
    }
    if (filterSelect) {
        filterSelect.addEventListener('change', filterGallery);
    }
    
    setTimeout(initScrollAnimations, 100);
});

const style = document.createElement('style');
style.textContent = `
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
`;
document.head.appendChild(style);
