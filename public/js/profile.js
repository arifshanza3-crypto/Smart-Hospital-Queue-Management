/**
 * پروفائل پیج کی JavaScript
 * ایوٹار اپلوڈ اور دیگر انٹریکشنز
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ============================================
    // ایوٹار اپلوڈ
    // ============================================
    
    window.uploadAvatar = function(input) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        
        // فائل کی قسم چیک کریں
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showMessage('براہ کرم درست تصویر اپلوڈ کریں (JPEG, PNG, JPG, GIF, WebP)', 'error');
            input.value = '';
            return;
        }

        // فائل کا سائز چیک کریں (2MB سے کم)
        if (file.size > 2 * 1024 * 1024) {
            showMessage('تصویر کا سائز 2MB سے کم ہونا چاہیے', 'error');
            input.value = '';
            return;
        }

        const formData = new FormData();
        formData.append('avatar', file);

        const avatarDiv = document.querySelector('.profile-avatar');
        const originalContent = avatarDiv.innerHTML;
        
        // لوڈنگ اسٹیٹ
        avatarDiv.style.opacity = '0.6';
        avatarDiv.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:30px; color:white;"></i>';

        const token = document.querySelector('meta[name="csrf-token"]')?.content || 
                     document.querySelector('input[name="_token"]')?.value;

        fetch('/profile/avatar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateAvatarDisplay(data.avatar_url);
                showMessage('ایوٹار کامیابی سے اپڈیٹ ہو گیا!', 'success');
            } else {
                showMessage(data.message || 'ایوٹار اپڈیٹ کرنے میں خرابی', 'error');
                avatarDiv.innerHTML = originalContent;
            }
        })
        .catch(error => {
            console.error('Upload Error:', error);
            showMessage('ایوٹار اپلوڈ کرنے میں خرابی۔ براہ کرم دوبارہ کوشش کریں۔', 'error');
            avatarDiv.innerHTML = originalContent;
        })
        .finally(() => {
            avatarDiv.style.opacity = '1';
            input.value = '';
        });
    };

    function updateAvatarDisplay(avatarUrl) {
        const avatarDiv = document.querySelector('.profile-avatar');
        avatarDiv.innerHTML = '';
        
        const img = document.createElement('img');
        img.src = avatarUrl + '?t=' + Date.now();
        img.alt = 'Profile Avatar';
        img.id = 'profileAvatar';
        avatarDiv.appendChild(img);
    }

    // ============================================
    // ڈریگ اینڈ ڈراپ سپورٹ
    // ============================================

    const avatarWrapper = document.querySelector('.profile-avatar-wrapper');
    
    if (avatarWrapper) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            avatarWrapper.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            avatarWrapper.addEventListener(eventName, () => {
                avatarWrapper.style.opacity = '0.7';
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            avatarWrapper.addEventListener(eventName, () => {
                avatarWrapper.style.opacity = '1';
            }, false);
        });

        avatarWrapper.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const fileInput = document.getElementById('avatarInput');
                if (fileInput) {
                    fileInput.files = files;
                    uploadAvatar(fileInput);
                }
            }
        }, false);
    }

    // ============================================
    // میسج سسٹم
    // ============================================

    window.showMessage = function(message, type) {
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(el => el.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;

        const wrapper = document.querySelector('.profile-wrapper');
        if (wrapper) {
            wrapper.prepend(alertDiv);
        }

        setTimeout(() => {
            alertDiv.style.transition = 'opacity 0.3s ease';
            alertDiv.style.opacity = '0';
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 300);
        }, 5000);
    };

    // ============================================
    // ای میل کاپی کریں
    // ============================================

    const emailElement = document.querySelector('.profile-info .email');
    if (emailElement) {
        emailElement.style.cursor = 'pointer';
        emailElement.title = 'ای میل کاپی کرنے کے لیے کلک کریں';
        
        emailElement.addEventListener('click', function() {
            const email = this.textContent.trim();
            
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(email).then(() => {
                    showMessage('ای میل کاپی ہو گئی!', 'success');
                }).catch(() => {
                    fallbackCopy(email);
                });
            } else {
                fallbackCopy(email);
            }
        });

        function fallbackCopy(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            textArea.style.top = '-9999px';
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                showMessage('ای میل کاپی ہو گئی!', 'success');
            } catch (err) {
                showMessage('ای میل کاپی نہیں ہو سکی', 'error');
            }
            textArea.remove();
        }
    }

    // ============================================
    // کی بورڈ شارٹ کٹس
    // ============================================

    document.addEventListener('keydown', function(e) {
        if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
            if (e.key === 'e' || e.key === 'E') {
                const editBtn = document.querySelector('.btn-edit');
                if (editBtn) {
                    e.preventDefault();
                    editBtn.click();
                }
            }
        }
    });

    // ============================================
    // کنسول لاگ
    // ============================================

    console.log('✅ پروفائل پیج لوڈ ہو گیا');
    console.log('📋 ٹپس:');
    console.log('  • پروفائل ایڈٹ کرنے کے لیے "E" پریس کریں');
    console.log('  • ای میل کاپی کرنے کے لیے کلک کریں');
    console.log('  • ایوٹار تبدیل کرنے کے لیے تصویر ڈریگ کریں');
});