/* ==========================================
   Main JavaScript
   ========================================== */

// Show toast notification
function showToast(message, type = 'success') {
    const toastHTML = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.insertAdjacentHTML('beforeend', toastHTML);
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

// Format time ago
function timeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    const intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60
    };
    
    for (const [unit, secondsInUnit] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / secondsInUnit);
        if (interval >= 1) {
            return interval + ' ' + unit + (interval === 1 ? '' : 's') + ' ago';
        }
    }
    
    return 'Just now';
}

// Generate star rating HTML
function generateStarRating(rating, maxRating = 5) {
    let stars = '';
    for (let i = 1; i <= maxRating; i++) {
        if (i <= rating) {
            stars += '<i class="bi bi-star-fill text-warning"></i>';
        } else {
            stars += '<i class="bi bi-star text-muted"></i>';
        }
    }
    return stars;
}

// Update navbar based on auth status
function updateNavbar() {
    const navbar = document.querySelector('.navbar-nav');
    if (!navbar) return;
    
    if (typeof isAuthenticated === 'function' && isAuthenticated()) {
        const user = getCurrentUser();
        const userNavHTML = `
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i> ${user.name}
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="${user.role === 'admin' ? 'admin-dashboard.html' : 'user-dashboard.html'}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" onclick="logout()">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </a></li>
                </ul>
            </li>
        `;
        
        // Remove login/register buttons
        const loginBtn = navbar.querySelector('a[href*="login"]');
        const registerBtn = navbar.querySelector('a[href*="register"]');
        if (loginBtn) loginBtn.parentElement.remove();
        if (registerBtn) registerBtn.parentElement.remove();
        
        // Add user menu
        navbar.insertAdjacentHTML('beforeend', userNavHTML);
    }
}

// Initialize tooltips
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Initialize popovers
function initPopovers() {
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

// Loading overlay
function showLoading() {
    const overlay = document.createElement('div');
    overlay.className = 'spinner-overlay';
    overlay.id = 'loadingOverlay';
    overlay.innerHTML = '<div class="spinner-border text-light spinner-overlay-spinner" role="status"><span class="visually-hidden">Loading...</span></div>';
    document.body.appendChild(overlay);
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.remove();
    }
}

// Smooth scroll to section
function smoothScroll(target) {
    const element = document.querySelector(target);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Password visibility toggle
function initPasswordToggles() {
    document.querySelectorAll('[data-toggle-password]').forEach(function(button) {
        button.addEventListener('click', function() {
            const targetSelector = this.getAttribute('data-target');
            const input = document.querySelector(targetSelector);
            const icon = this.querySelector('i');

            if (!input || !icon) {
                return;
            }

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
}

function initChatScroll() {
    const chatScroll = document.querySelector('[data-chat-scroll]');
    const messageThread = document.querySelector('[data-message-thread]');

    [chatScroll, messageThread].forEach(function(element) {
        if (!element) {
            return;
        }

        element.scrollTop = element.scrollHeight;
    });
}

function initLoadingButtons() {
    document.querySelectorAll('form').forEach(function(form) {
        if (form.dataset.loadingBound === '1') {
            return;
        }

        form.dataset.loadingBound = '1';
        form.addEventListener('submit', function() {
            const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');

            submitButtons.forEach(function(button) {
                if (button.disabled) {
                    return;
                }

                button.disabled = true;
                button.classList.add('is-loading');

                if (button.tagName === 'BUTTON') {
                    const spinner = button.querySelector('.spinner-border');
                    if (spinner) {
                        spinner.classList.remove('d-none');
                    } else {
                        const spinner = document.createElement('span');
                        spinner.className = 'spinner-border spinner-border-sm me-2';
                        spinner.setAttribute('aria-hidden', 'true');
                        button.prepend(spinner);
                    }
                } else {
                    button.value = 'Loading...';
                }
            });
        });
    });
}

function initSidebarToggle() {
    const sidebar = document.querySelector('.dashboard-sidebar');
    const toggleButtons = document.querySelectorAll('[data-sidebar-toggle]');
    const backdrop = document.querySelector('[data-sidebar-backdrop]');

    if (!sidebar || !toggleButtons.length) {
        return;
    }

    const isMobile = () => window.matchMedia('(max-width: 991.98px)').matches;

    const closeSidebar = () => {
        document.body.classList.remove('sidebar-open');
    };

    const applyMode = () => {
        if (isMobile()) {
            document.body.classList.remove('sidebar-collapsed');
            closeSidebar();
        } else {
            closeSidebar();
        }
    };

    toggleButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            if (isMobile()) {
                document.body.classList.toggle('sidebar-open');
                return;
            }

            document.body.classList.toggle('sidebar-collapsed');
        });
    });

    if (backdrop) {
        backdrop.addEventListener('click', closeSidebar);
    }

    window.addEventListener('resize', applyMode);
    applyMode();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap components
    initTooltips();
    initPopovers();
    initPasswordToggles();
    initChatScroll();
    initLoadingButtons();
    initSidebarToggle();
    document.querySelectorAll('.toast').forEach(function (element) {
        new bootstrap.Toast(element, { delay: 3500 }).show();
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href !== '#!') {
                e.preventDefault();
                smoothScroll(href);
            }
        });
    });
});

/* ==========================================
   Design Data and Demo Auth Utilities
   ========================================== */

const majors = [
    { id: 'business', name: 'Business & Management', icon: 'bi-briefcase', color: 'primary' },
    { id: 'technology', name: 'Technology & IT', icon: 'bi-cpu', color: 'info' },
    { id: 'healthcare', name: 'Healthcare & Medicine', icon: 'bi-heart-pulse', color: 'danger' },
    { id: 'engineering', name: 'Engineering', icon: 'bi-gear', color: 'warning' },
    { id: 'education', name: 'Education', icon: 'bi-book', color: 'success' },
    { id: 'arts', name: 'Arts & Design', icon: 'bi-palette', color: 'secondary' }
];

const jobRoles = [
    {
        id: 'job1',
        title: 'Software Engineer',
        major: 'Technology & IT',
        subMajor: 'Software Development',
        description: 'Design, develop, and maintain software applications and systems',
        salaryRange: '12,000 - 25,000 SAR/month',
        averageRating: 4.5,
        evaluations: []
    },
    {
        id: 'job2',
        title: 'Data Analyst',
        major: 'Technology & IT',
        subMajor: 'Data Science',
        description: 'Analyze complex data sets to help organizations make informed decisions',
        salaryRange: '10,000 - 20,000 SAR/month',
        averageRating: 4.3,
        evaluations: []
    }
];

function getJobsByMajor(majorId) {
    const majorName = majors.find(m => m.id === majorId)?.name;
    return jobRoles.filter(job => job.major === majorName);
}

function getJobById(jobId) {
    return jobRoles.find(job => job.id === jobId);
}

function searchJobs(query) {
    const lowerQuery = query.toLowerCase();
    return jobRoles.filter(job =>
        job.title.toLowerCase().includes(lowerQuery) ||
        job.major.toLowerCase().includes(lowerQuery) ||
        job.description.toLowerCase().includes(lowerQuery)
    );
}

function isAuthenticated() {
    const user = localStorage.getItem('currentUser');
    return user !== null;
}

function getCurrentUser() {
    const userStr = localStorage.getItem('currentUser');
    return userStr ? JSON.parse(userStr) : null;
}

function login(email, password) {
    if (email === 'admin@careerinsights.sa' && password === 'admin123') {
        const adminUser = { id: 'admin1', name: 'Admin User', email: email, role: 'admin' };
        localStorage.setItem('currentUser', JSON.stringify(adminUser));
        return { success: true, user: adminUser };
    }

    return { success: false, error: 'Invalid email or password' };
}

function register(name, email, password) {
    const userData = { id: 'user' + Date.now(), name: name, email: email, role: 'user' };
    localStorage.setItem('currentUser', JSON.stringify(userData));
    return { success: true, user: userData };
}

function logout() {
    localStorage.removeItem('currentUser');
    window.location.href = '/';
}

// Form validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
        
        // Email validation
        if (input.type === 'email' && input.value && !validateEmail(input.value)) {
            input.classList.add('is-invalid');
            isValid = false;
        }
    });
    
    return isValid;
}

// Clear form validation
function clearFormValidation(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.querySelectorAll('.is-invalid, .is-valid').forEach(input => {
        input.classList.remove('is-invalid', 'is-valid');
    });
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
