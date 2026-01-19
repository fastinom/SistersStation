// Import Bootstrap
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Global JavaScript functions
window.showToast = function(message, type = 'success') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    toastContainer.innerHTML = toastHtml;
    
    document.body.appendChild(toastContainer);
    
    const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
    toast.show();
    
    toastContainer.querySelector('.toast').addEventListener('hidden.bs.toast', () => {
        toastContainer.remove();
    });
};

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize Bootstrap popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

// Utility functions
window.formatCurrency = function(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
};

window.formatDate = function(date) {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

window.debounce = function(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};

// Image lazy loading
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});

// Form validation helpers
window.validateForm = function(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
};

// AJAX helper
window.ajaxRequest = function(url, options = {}) {
    const defaults = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    };
    
    return fetch(url, { ...defaults, ...options });
};

// Cart functionality
window.updateCartCount = function() {
    fetch('/api/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = data.count;
            });
        })
        .catch(error => console.error('Error updating cart count:', error));
};

// Search suggestions
window.initSearchSuggestions = function(inputId, suggestionsUrl) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    let timeout;
    
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            hideSuggestions();
            return;
        }
        
        timeout = setTimeout(() => {
            fetch(`${suggestionsUrl}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    showSuggestions(data, input);
                })
                .catch(error => console.error('Error fetching suggestions:', error));
        }, 300);
    });
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-suggestions')) {
            hideSuggestions();
        }
    });
};

function showSuggestions(suggestions, input) {
    hideSuggestions();
    
    if (!suggestions.products?.length && !suggestions.categories?.length && !suggestions.sellers?.length) {
        return;
    }
    
    const suggestionsDiv = document.createElement('div');
    suggestionsDiv.className = 'search-suggestions position-absolute w-100 bg-white border rounded shadow-lg mt-1';
    suggestionsDiv.style.zIndex = '1000';
    
    let html = '';
    
    if (suggestions.products?.length) {
        html += '<div class="p-2"><h6 class="text-muted mb-2">Products</h6>';
        suggestions.products.forEach(product => {
            html += `<a href="${product.url}" class="d-block p-2 text-decoration-none text-dark hover-bg-light">
                ${product.name}
            </a>`;
        });
        html += '</div>';
    }
    
    if (suggestions.categories?.length) {
        html += '<div class="p-2"><h6 class="text-muted mb-2">Categories</h6>';
        suggestions.categories.forEach(category => {
            html += `<a href="${category.url}" class="d-block p-2 text-decoration-none text-dark hover-bg-light">
                ${category.name}
            </a>`;
        });
        html += '</div>';
    }
    
    if (suggestions.sellers?.length) {
        html += '<div class="p-2"><h6 class="text-muted mb-2">Sellers</h6>';
        suggestions.sellers.forEach(seller => {
            html += `<a href="${seller.url}" class="d-block p-2 text-decoration-none text-dark hover-bg-light">
                ${seller.name}
            </a>`;
        });
        html += '</div>';
    }
    
    suggestionsDiv.innerHTML = html;
    
    const rect = input.getBoundingClientRect();
    suggestionsDiv.style.top = (rect.bottom + window.scrollY) + 'px';
    suggestionsDiv.style.left = rect.left + 'px';
    suggestionsDiv.style.width = rect.width + 'px';
    
    document.body.appendChild(suggestionsDiv);
}

function hideSuggestions() {
    const suggestions = document.querySelector('.search-suggestions');
    if (suggestions) {
        suggestions.remove();
    }
}

// Product image gallery
window.initProductGallery = function() {
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    if (!mainImage || !thumbnails.length) return;
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            mainImage.src = this.src;
            
            // Update active state
            thumbnails.forEach(t => t.classList.remove('border-primary'));
            this.classList.add('border-primary');
        });
    });
};

// Quantity selector
window.initQuantitySelector = function() {
    const decreaseBtn = document.querySelector('.quantity-decrease');
    const increaseBtn = document.querySelector('.quantity-increase');
    const input = document.querySelector('.quantity-input');
    
    if (!decreaseBtn || !increaseBtn || !input) return;
    
    decreaseBtn.addEventListener('click', function() {
        const currentValue = parseInt(input.value);
        const minValue = parseInt(input.min) || 1;
        if (currentValue > minValue) {
            input.value = currentValue - 1;
            input.dispatchEvent(new Event('change'));
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        const currentValue = parseInt(input.value);
        const maxValue = parseInt(input.max) || 999;
        if (currentValue < maxValue) {
            input.value = currentValue + 1;
            input.dispatchEvent(new Event('change'));
        }
    });
};

// Rating stars
window.initRatingStars = function() {
    const stars = document.querySelectorAll('.rating-star');
    
    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.remove('btn-outline-warning');
                    s.classList.add('btn-warning');
                    s.querySelector('i').classList.remove('bi-star');
                    s.querySelector('i').classList.add('bi-star-fill');
                } else {
                    s.classList.remove('btn-warning');
                    s.classList.add('btn-outline-warning');
                    s.querySelector('i').classList.remove('bi-star-fill');
                    s.querySelector('i').classList.add('bi-star');
                }
            });
            
            const ratingInput = document.querySelector('input[name="rating"]');
            if (ratingInput) {
                ratingInput.value = rating;
            }
        });
    });
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initProductGallery();
    initQuantitySelector();
    initRatingStars();
});
