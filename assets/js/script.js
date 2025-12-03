/**
 * File JavaScript chính của ứng dụng
 */

// Tự động ẩn thông báo sau 5 giây
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Xác nhận xóa
function xácNhậnXóa(thông_điệp) {
    return confirm(thông_điệp || 'Bạn có chắc chắn muốn xóa?');
}

// Validate form
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    inputs.forEach(function(input) {
        if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    });
    
    return isValid;
}

// Tìm kiếm khóa học
function tìmKiếmKhóaHọc() {
    const keyword = document.getElementById('search-keyword');
    if (keyword && keyword.value.trim()) {
        window.location.href = 'index.php?controller=course&action=search&keyword=' + encodeURIComponent(keyword.value.trim());
    }
}

// Lọc theo danh mục
function lọcTheoDanhMục(categoryId) {
    window.location.href = 'index.php?controller=course&action=index&category_id=' + categoryId;
}
