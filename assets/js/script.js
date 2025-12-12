/**
 * File JavaScript chính - Hệ thống Khóa học Online
 * Phiên bản đơn giản - không animation
 */

document.addEventListener('DOMContentLoaded', function() {
    // Ẩn thông báo sau 5 giây
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // Khởi tạo drag & drop
    initDragAndDrop('avatar-upload-area', 'avatar-input');
    initDragAndDrop('material-upload-area', 'material-input');
});

// Xác nhận xóa
function xácNhậnXóa(msg) {
    return confirm(msg || 'Bạn có chắc chắn muốn xóa?');
}

// Validate form
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    let isValid = true;
    form.querySelectorAll('[required]').forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '';
        }
    });
    return isValid;
}

function validateUploadForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const fileInput = form.querySelector('input[type="file"]');
    if (fileInput && !fileInput.files.length) {
        alert('Vui lòng chọn file để upload!');
        return false;
    }
    return validateForm(formId);
}

// Tìm kiếm
function tìmKiếmKhóaHọc() {
    const keyword = document.getElementById('search-keyword');
    if (keyword && keyword.value.trim()) {
        window.location.href = 'index.php?controller=course&action=search&keyword=' + encodeURIComponent(keyword.value.trim());
    }
}

function lọcTheoDanhMục(categoryId) {
    window.location.href = 'index.php?controller=course&action=index&category_id=' + categoryId;
}

// Preview avatar
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

// Preview material
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
        fileInfo.innerHTML = '<strong>File:</strong> ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
    }
}

// Preview lesson image
function previewLessonImage(input) {
    if (!input.files || !input.files[0]) return;
    
    const file = input.files[0];
    if (!file.type.match('image.*')) {
        alert('Vui lòng chọn file ảnh!');
        input.value = '';
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        alert('Ảnh không được vượt quá 5MB!');
        input.value = '';
        return;
    }
    
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('lesson-image-preview');
        if (preview) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Lesson Image" style="max-width: 300px; border-radius: 8px;">';
        }
    };
    reader.readAsDataURL(file);
}

// Drag and drop
function initDragAndDrop(areaId, inputId) {
    const area = document.getElementById(areaId);
    const input = document.getElementById(inputId);
    
    if (!area || !input) return;
    
    area.addEventListener('click', () => input.click());
    
    area.addEventListener('dragover', e => {
        e.preventDefault();
        area.classList.add('dragover');
    });
    
    area.addEventListener('dragleave', () => {
        area.classList.remove('dragover');
    });
    
    area.addEventListener('drop', e => {
        e.preventDefault();
        area.classList.remove('dragover');
        
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            input.dispatchEvent(new Event('change'));
        }
    });
}

// Tooltips
function initTooltips() {
    document.querySelectorAll('[title]').forEach(el => {
        el.style.cursor = 'help';
    });
}


