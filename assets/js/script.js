/**
 * File JavaScript chính - Hệ thống Khóa học Online
 * Tác giả: Nhóm 4 - Tú
 */

// ========== TỰ ĐỘNG ẨN THÔNG BÁO ==========
document.addEventListener('DOMContentLoaded', function() {
    // Ẩn thông báo sau 5 giây
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Khởi tạo các chức năng
    initTooltips();
    initDragAndDrop('avatar-upload-area', 'avatar-input');
    initDragAndDrop('material-upload-area', 'material-input');
    uploadFileWithProgress('avatar-upload-form', 'avatar-progress');
    uploadFileWithProgress('material-upload-form', 'material-progress');
});

// ========== XÁC NHẬN XÓA ==========
function xácNhậnXóa(msg) {
    return confirm(msg || 'Bạn có chắc chắn muốn xóa?');
}

// ========== VALIDATE FORM ==========
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    form.querySelectorAll('[required]').forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    });
    return isValid;
}

function validateUploadForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const fileInput = form.querySelector('input[type="file"]');
    if (fileInput && !fileInput.files.length) {
        alert('Vui lòng chọn file để upload!');
        return false;
    }
    return validateForm(formId);
}

// ========== TÌM KIẾM & LỌC ==========
function tìmKiếmKhóaHọc() {
    const keyword = document.getElementById('search-keyword');
    if (keyword && keyword.value.trim()) {
        window.location.href = 'index.php?controller=course&action=search&keyword=' + encodeURIComponent(keyword.value.trim());
    }
}

function lọcTheoDanhMục(categoryId) {
    window.location.href = 'index.php?controller=course&action=index&category_id=' + categoryId;
}

// ========== PREVIEW FILE ==========
function previewAvatar(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    if (!file.type.match('image.*')) {
        alert('Vui lòng chọn file ảnh!');
        input.value = '';
        return;
    }
    if (file.size > 2 * 1024 * 1024) {
        alert('Ảnh không được vượt quá 2MB!');
        input.value = '';
        return;
    }
    
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('avatar-preview');
        if (preview) preview.innerHTML = '<img src="' + e.target.result + '" alt="Avatar">';
    };
    reader.readAsDataURL(file);
}

function previewMaterial(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    ];
    
    if (!allowedTypes.includes(file.type)) {
        alert('Chỉ chấp nhận file PDF, DOC, DOCX, PPT, PPTX!');
        input.value = '';
        return;
    }
    if (file.size > 10 * 1024 * 1024) {
        alert('File không được vượt quá 10MB!');
        input.value = '';
        return;
    }
    
    const fileInfo = document.getElementById('file-info');
    if (fileInfo) {
        fileInfo.innerHTML = `<strong>File:</strong> ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        fileInfo.style.display = 'block';
    }
}

// ========== DRAG & DROP ==========
function initDragAndDrop(areaId, inputId) {
    const area = document.getElementById(areaId);
    const input = document.getElementById(inputId);
    if (!area || !input) return;
    
    area.addEventListener('click', () => input.click());
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => {
        area.addEventListener(e, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(e => {
        area.addEventListener(e, () => area.classList.add('dragover'), false);
    });
    
    ['dragleave', 'drop'].forEach(e => {
        area.addEventListener(e, () => area.classList.remove('dragover'), false);
    });
    
    area.addEventListener('drop', e => {
        input.files = e.dataTransfer.files;
        if (inputId.includes('avatar')) previewAvatar(input);
        else if (inputId.includes('material')) previewMaterial(input);
    }, false);
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

// ========== UPLOAD VỚI PROGRESS ==========
function uploadFileWithProgress(formId, progressId) {
    const form = document.getElementById(formId);
    const progressBar = document.getElementById(progressId);
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const xhr = new XMLHttpRequest();
        const formData = new FormData(form);
        
        if (progressBar) {
            progressBar.style.display = 'block';
            const fill = progressBar.querySelector('.progress-fill');
            
            xhr.upload.addEventListener('progress', e => {
                if (e.lengthComputable) {
                    const percent = (e.loaded / e.total) * 100;
                    fill.style.width = percent + '%';
                    fill.textContent = Math.round(percent) + '%';
                }
            });
        }
        
        xhr.addEventListener('load', () => {
            if (xhr.status === 200) {
                try {
                    const res = JSON.parse(xhr.responseText);
                    alert(res.success ? 'Upload thành công!' : 'Lỗi: ' + res.message);
                } catch (e) {}
                window.location.reload();
            } else {
                alert('Lỗi server!');
            }
            if (progressBar) progressBar.style.display = 'none';
        });
        
        xhr.addEventListener('error', () => {
            alert('Lỗi kết nối!');
            if (progressBar) progressBar.style.display = 'none';
        });
        
        xhr.open('POST', form.action);
        xhr.send(formData);
    });
}

// ========== MODAL ==========
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('show');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('show');
}

document.addEventListener('click', e => {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('show');
    }
});

// ========== TOOLTIP ==========
function initTooltips() {
    document.querySelectorAll('.tooltip').forEach(el => {
        const text = el.getAttribute('data-tooltip');
        if (text) {
            const span = document.createElement('span');
            span.className = 'tooltip-text';
            span.textContent = text;
            el.appendChild(span);
        }
    });
}

// ========== TIỆN ÍCH ==========
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const icons = {
        'pdf': '📄', 'doc': '📝', 'docx': '📝',
        'ppt': '📊', 'pptx': '📊', 'xls': '📊', 'xlsx': '📊',
        'jpg': '🖼️', 'jpeg': '🖼️', 'png': '🖼️', 'gif': '🖼️',
        'zip': '📦', 'rar': '📦'
    };
    return icons[ext] || '📎';
}

// ========================================
// HIỆU ỨNG LẬT TRANG POWERPOINT
// ========================================

// Scroll Animation - Hiệu ứng khi scroll
function initScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                // Thêm class animation tùy theo loại element
                if (entry.target.classList.contains('course-card')) {
                    entry.target.style.animation = 'stackFlip 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards';
                } else if (entry.target.classList.contains('stat-card')) {
                    entry.target.style.animation = 'zoomFlipIn 0.7s cubic-bezier(0.4, 0, 0.2, 1) forwards';
                } else if (entry.target.classList.contains('section')) {
                    entry.target.style.animation = 'pageFlipIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards';
                }
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Observe tất cả elements cần animation
    document.querySelectorAll('.course-card, .stat-card, .category-card, section, .lesson-item').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });
}

// Page Transition - Hiệu ứng chuyển trang
function initPageTransitions() {
    // Thêm class page-flip khi load trang
    document.body.classList.add('page-loaded');
    
    // Hiệu ứng khi click link
    document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([href^="javascript"])').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && !href.startsWith('#') && !href.startsWith('javascript')) {
                e.preventDefault();
                
                // Thêm hiệu ứng flip out
                document.body.classList.add('page-flip-out');
                
                // Chuyển trang sau khi animation kết thúc
                setTimeout(() => {
                    window.location.href = href;
                }, 600);
            }
        });
    });
}

// Stagger Animation - Hiệu ứng lần lượt cho danh sách
function initStaggerAnimations() {
    const staggerElements = document.querySelectorAll('.course-card, .stat-card, .category-card, table tbody tr, .lesson-item');
    
    staggerElements.forEach((el, index) => {
        el.style.animationDelay = `${index * 0.1}s`;
    });
}

// Parallax Effect - Hiệu ứng parallax khi scroll
function initParallax() {
    const hero = document.querySelector('.hero');
    if (hero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            hero.style.transform = `translateY(${scrolled * 0.3}px)`;
        });
    }
}

// 3D Tilt Effect - Hiệu ứng nghiêng 3D khi hover card
function init3DTilt() {
    document.querySelectorAll('.course-card, .stat-card, .category-card, .auth-box, .profile-card').forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;
            
            this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });
}

// Smooth Scroll - Cuộn mượt
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Neon Glow Follow Mouse - Hiệu ứng neon theo chuột
function initNeonGlow() {
    const cards = document.querySelectorAll('.course-card, .auth-box, .profile-card');
    
    cards.forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            this.style.setProperty('--mouse-x', `${x}px`);
            this.style.setProperty('--mouse-y', `${y}px`);
        });
    });
}

// Typing Effect - Hiệu ứng gõ chữ
function initTypingEffect() {
    const heroTitle = document.querySelector('.hero h1');
    if (heroTitle) {
        const text = heroTitle.textContent;
        heroTitle.textContent = '';
        heroTitle.style.opacity = '1';
        
        let i = 0;
        const typeWriter = () => {
            if (i < text.length) {
                heroTitle.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        };
        
        setTimeout(typeWriter, 500);
    }
}

// Counter Animation - Đếm số từ 0
function initCounterAnimation() {
    const counters = document.querySelectorAll('.stat-card h3');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const finalValue = parseInt(target.textContent.replace(/\D/g, ''));
                
                if (finalValue) {
                    let current = 0;
                    const increment = finalValue / 50;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= finalValue) {
                            target.textContent = finalValue;
                            clearInterval(timer);
                        } else {
                            target.textContent = Math.floor(current);
                        }
                    }, 30);
                }
                
                observer.unobserve(target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => observer.observe(counter));
}

// Magnetic Button - Nút hút theo chuột
function initMagneticButtons() {
    document.querySelectorAll('.btn-primary, .btn-secondary').forEach(btn => {
        btn.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            
            this.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translate(0, 0)';
        });
    });
}

// Reveal on Scroll - Hiện dần khi scroll
function initRevealOnScroll() {
    const reveals = document.querySelectorAll('.hero, .course-grid, .category-grid, .stats, .content');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, { threshold: 0.1 });
    
    reveals.forEach(el => {
        el.classList.add('reveal');
        observer.observe(el);
    });
}

// Khởi tạo tất cả hiệu ứng khi DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo các hiệu ứng PowerPoint
    initScrollAnimations();
    initPageTransitions();
    initStaggerAnimations();
    init3DTilt();
    initSmoothScroll();
    initNeonGlow();
    initCounterAnimation();
    initMagneticButtons();
    initRevealOnScroll();
    // initTypingEffect(); // Bỏ comment nếu muốn hiệu ứng gõ chữ
    // initParallax(); // Bỏ comment nếu muốn hiệu ứng parallax
});

// CSS bổ sung được inject vào trang
const pageFlipStyles = document.createElement('style');
pageFlipStyles.textContent = `
    /* Page Transition Out */
    body.page-flip-out {
        animation: pageFlipOut 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    
    body.page-loaded {
        animation: pageFlipIn 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    
    /* Reveal Animation */
    .reveal {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .reveal.revealed {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Animate In */
    .animate-in {
        opacity: 1 !important;
    }
    
    /* Card Neon Glow Effect */
    .course-card::before,
    .auth-box::before,
    .profile-card::before {
        content: '';
        position: absolute;
        top: var(--mouse-y, 50%);
        left: var(--mouse-x, 50%);
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255, 0, 255, 0.3), transparent 70%);
        transform: translate(-50%, -50%);
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
        z-index: 0;
    }
    
    .course-card:hover::before,
    .auth-box:hover::before,
    .profile-card:hover::before {
        opacity: 1;
    }
    
    /* 3D Card Transition */
    .course-card,
    .stat-card,
    .category-card,
    .auth-box,
    .profile-card {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s;
        transform-style: preserve-3d;
    }
    
    /* Lenis Smooth Scroll Body */
    html.lenis {
        height: auto;
    }
    
    .lenis.lenis-smooth {
        scroll-behavior: auto;
    }
    
    /* Horizontal Scroll Section */
    .horizontal-scroll-container {
        width: 100%;
        overflow: hidden;
    }
    
    .horizontal-scroll-wrapper {
        display: flex;
        gap: 40px;
        will-change: transform;
    }
    
    .horizontal-scroll-item {
        flex-shrink: 0;
        width: 80vw;
        max-width: 600px;
    }
    
    /* Sticky Section */
    .sticky-section {
        position: sticky;
        top: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Text Split Animation */
    .split-text .char {
        display: inline-block;
        opacity: 0;
        transform: translateY(50px) rotateX(-90deg);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .split-text.animate .char {
        opacity: 1;
        transform: translateY(0) rotateX(0);
    }
    
    /* Scale on Scroll */
    .scale-on-scroll {
        transition: transform 0.3s ease;
    }
    
    /* Custom Cursor */
    .custom-cursor {
        position: fixed;
        width: 20px;
        height: 20px;
        border: 2px solid #ff00ff;
        border-radius: 50%;
        pointer-events: none;
        z-index: 9999;
        transition: transform 0.15s ease, width 0.3s, height 0.3s, background 0.3s;
        mix-blend-mode: difference;
    }
    
    .custom-cursor.hover {
        width: 50px;
        height: 50px;
        background: rgba(255, 0, 255, 0.2);
    }
    
    .custom-cursor-dot {
        position: fixed;
        width: 6px;
        height: 6px;
        background: #00ffff;
        border-radius: 50%;
        pointer-events: none;
        z-index: 9999;
    }
    
    /* Page Transition Overlay */
    .page-transition-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #0d0221;
        z-index: 9998;
        transform: translateY(100%);
        pointer-events: none;
    }
    
    .page-transition-overlay.active {
        animation: slideUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    
    @keyframes slideUp {
        0% { transform: translateY(100%); }
        50% { transform: translateY(0%); }
        100% { transform: translateY(-100%); }
    }
`;
document.head.appendChild(pageFlipStyles);

// ========================================
// WEAREBRAND.IO EFFECTS - TẤT CẢ HIỆU ỨNG
// ========================================

// 1. LENIS SMOOTH SCROLL - Cuộn mượt như bơ
class SmoothScroll {
    constructor() {
        this.current = 0;
        this.target = 0;
        this.ease = 0.075;
        this.init();
    }
    
    init() {
        document.documentElement.classList.add('lenis');
        this.animate();
        window.addEventListener('scroll', () => {
            this.target = window.scrollY;
        });
    }
    
    lerp(start, end, factor) {
        return start + (end - start) * factor;
    }
    
    animate() {
        this.current = this.lerp(this.current, this.target, this.ease);
        
        // Apply smooth scroll to elements
        document.querySelectorAll('[data-scroll-speed]').forEach(el => {
            const speed = parseFloat(el.dataset.scrollSpeed) || 0.5;
            const yPos = -(this.current * speed);
            el.style.transform = `translateY(${yPos}px)`;
        });
        
        requestAnimationFrame(() => this.animate());
    }
}

// 2. HORIZONTAL SCROLL - Cuộn ngang
function initHorizontalScroll() {
    const containers = document.querySelectorAll('.horizontal-scroll-container');
    
    containers.forEach(container => {
        const wrapper = container.querySelector('.horizontal-scroll-wrapper');
        if (!wrapper) return;
        
        const items = wrapper.children;
        const totalWidth = Array.from(items).reduce((acc, item) => acc + item.offsetWidth + 40, 0);
        
        window.addEventListener('scroll', () => {
            const rect = container.getBoundingClientRect();
            const scrollProgress = -rect.top / (container.offsetHeight - window.innerHeight);
            const translateX = Math.max(0, Math.min(1, scrollProgress)) * (totalWidth - window.innerWidth);
            
            wrapper.style.transform = `translateX(-${translateX}px)`;
        });
    });
}

// 3. PARALLAX EFFECT - Hiệu ứng parallax nâng cao
function initAdvancedParallax() {
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach(el => {
            const speed = parseFloat(el.dataset.parallax) || 0.5;
            const rect = el.getBoundingClientRect();
            const inView = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (inView) {
                const yPos = (rect.top - window.innerHeight) * speed;
                el.style.transform = `translate3d(0, ${yPos}px, 0)`;
            }
        });
    });
}

// 4. TEXT REVEAL ANIMATION - Chữ xuất hiện từng chữ
function initTextReveal() {
    const textElements = document.querySelectorAll('.hero h1, .hero p, .section-title, h2');
    
    textElements.forEach(el => {
        if (el.classList.contains('split-done')) return;
        
        const text = el.textContent;
        el.innerHTML = '';
        el.classList.add('split-text');
        
        text.split('').forEach((char, i) => {
            const span = document.createElement('span');
            span.className = 'char';
            span.textContent = char === ' ' ? '\u00A0' : char;
            span.style.transitionDelay = `${i * 0.03}s`;
            el.appendChild(span);
        });
        
        el.classList.add('split-done');
    });
    
    // Trigger animation when in view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
            }
        });
    }, { threshold: 0.5 });
    
    document.querySelectorAll('.split-text').forEach(el => observer.observe(el));
}

// 5. SCALE ON SCROLL - Phóng to/thu nhỏ khi scroll
function initScaleOnScroll() {
    const scaleElements = document.querySelectorAll('.course-image img, .hero-image, [data-scale]');
    
    window.addEventListener('scroll', () => {
        scaleElements.forEach(el => {
            const rect = el.getBoundingClientRect();
            const inView = rect.top < window.innerHeight && rect.bottom > 0;
            
            if (inView) {
                const progress = 1 - (rect.top / window.innerHeight);
                const scale = 0.8 + (progress * 0.3);
                el.style.transform = `scale(${Math.min(1.1, Math.max(0.8, scale))})`;
            }
        });
    });
}

// 6. STICKY SECTIONS - Sections dính khi scroll
function initStickySections() {
    const sections = document.querySelectorAll('.sticky-section');
    
    sections.forEach((section, index) => {
        section.style.zIndex = sections.length - index;
    });
}

// 7. PAGE TRANSITIONS - Chuyển trang mượt
function initAdvancedPageTransitions() {
    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'page-transition-overlay';
    document.body.appendChild(overlay);
    
    // Handle link clicks
    document.querySelectorAll('a:not([target="_blank"]):not([href^="#"]):not([href^="javascript"]):not([href^="mailto"])').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (!href || href === '#') return;
            
            e.preventDefault();
            overlay.classList.add('active');
            
            setTimeout(() => {
                window.location.href = href;
            }, 600);
        });
    });
}

// 8. CUSTOM CURSOR - Đã tắt, dùng con trỏ mặc định
function initCustomCursor() {
    // Không tạo custom cursor - sử dụng con trỏ mặc định của hệ thống
    return;
}

// 9. MAGNETIC ELEMENTS - Elements bị hút theo chuột
function initMagneticElements() {
    const magneticElements = document.querySelectorAll('.btn, .nav a, .logo a');
    
    magneticElements.forEach(el => {
        el.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            
            this.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
        });
        
        el.addEventListener('mouseleave', function() {
            this.style.transform = 'translate(0, 0)';
            this.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
        });
        
        el.addEventListener('mouseenter', function() {
            this.style.transition = 'none';
        });
    });
}

// 10. SPLIT TEXT ANIMATION - Animate từng chữ
function initSplitTextAnimation() {
    const titles = document.querySelectorAll('.hero h1');
    
    titles.forEach(title => {
        if (title.dataset.split) return;
        title.dataset.split = 'true';
        
        const words = title.textContent.split(' ');
        title.innerHTML = words.map((word, i) => 
            `<span class="word" style="animation-delay: ${i * 0.1}s">${word}</span>`
        ).join(' ');
    });
}

// 11. SCROLL PROGRESS INDICATOR
function initScrollProgress() {
    const progressBar = document.createElement('div');
    progressBar.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #ff00ff, #00ffff);
        z-index: 9999;
        transition: width 0.1s;
        box-shadow: 0 0 10px #ff00ff;
    `;
    document.body.appendChild(progressBar);
    
    window.addEventListener('scroll', () => {
        const scrollTop = window.pageYOffset;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = (scrollTop / docHeight) * 100;
        progressBar.style.width = `${progress}%`;
    });
}

// 12. IMAGE REVEAL ON SCROLL - Đã tắt để ảnh hiện bình thường
function initImageReveal() {
    // Tắt hiệu ứng này để ảnh hiện bình thường
    return;
}

// 13. FLOATING ANIMATION
function initFloatingAnimation() {
    const floatElements = document.querySelectorAll('.stat-card, .category-card');
    
    floatElements.forEach((el, i) => {
        el.style.animation = `float ${3 + i * 0.5}s ease-in-out infinite`;
        el.style.animationDelay = `${i * 0.2}s`;
    });
    
    // Add floating keyframes
    const floatKeyframes = document.createElement('style');
    floatKeyframes.textContent = `
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    `;
    document.head.appendChild(floatKeyframes);
}

// 14. TILT EFFECT ADVANCED
function initAdvancedTilt() {
    const tiltElements = document.querySelectorAll('.course-card, .stat-card, .auth-box');
    
    tiltElements.forEach(el => {
        el.addEventListener('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width;
            const y = (e.clientY - rect.top) / rect.height;
            
            const tiltX = (y - 0.5) * 20;
            const tiltY = (x - 0.5) * -20;
            
            this.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) scale(1.02)`;
            
            // Shine effect
            const shine = this.querySelector('.shine') || document.createElement('div');
            if (!this.querySelector('.shine')) {
                shine.className = 'shine';
                shine.style.cssText = `
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(
                        ${135 + (x - 0.5) * 50}deg,
                        transparent 40%,
                        rgba(255, 255, 255, 0.1) 50%,
                        transparent 60%
                    );
                    pointer-events: none;
                    z-index: 10;
                `;
                this.appendChild(shine);
            }
            shine.style.background = `linear-gradient(
                ${135 + (x - 0.5) * 50}deg,
                transparent 40%,
                rgba(255, 255, 255, 0.1) 50%,
                transparent 60%
            )`;
        });
        
        el.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
            this.style.transition = 'transform 0.5s ease';
            const shine = this.querySelector('.shine');
            if (shine) shine.remove();
        });
    });
}

// 15. LOADING ANIMATION
function initLoadingAnimation() {
    const loader = document.createElement('div');
    loader.className = 'page-loader';
    loader.innerHTML = `
        <div class="loader-content">
            <div class="loader-spinner"></div>
            <div class="loader-text">Loading...</div>
        </div>
    `;
    loader.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #0d0221;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        transition: opacity 0.5s, visibility 0.5s;
    `;
    
    const style = document.createElement('style');
    style.textContent = `
        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid #ff00ff40;
            border-top-color: #ff00ff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        .loader-text {
            margin-top: 20px;
            color: #00ffff;
            font-size: 1rem;
            letter-spacing: 3px;
        }
        .loader-content {
            text-align: center;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
    document.body.appendChild(loader);
    
    window.addEventListener('load', () => {
        setTimeout(() => {
            loader.style.opacity = '0';
            loader.style.visibility = 'hidden';
            setTimeout(() => loader.remove(), 500);
        }, 500);
    });
}

// KHỞI TẠO TẤT CẢ HIỆU ỨNG
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo loading
    initLoadingAnimation();
    
    // Smooth scroll
    new SmoothScroll();
    
    // Các hiệu ứng chính
    initAdvancedParallax();
    initTextReveal();
    initScaleOnScroll();
    initAdvancedPageTransitions();
    // initCustomCursor(); // Đã tắt - dùng con trỏ mặc định
    initMagneticElements();
    initScrollProgress();
    initImageReveal();
    initFloatingAnimation();
    initAdvancedTilt();
    
    // Hiệu ứng bổ sung
    // initHorizontalScroll(); // Uncomment nếu có horizontal section
    // initStickySections(); // Uncomment nếu có sticky section
    // initSplitTextAnimation();
    
    console.log('✨ Wearebrand.io Effects Loaded!');
});


