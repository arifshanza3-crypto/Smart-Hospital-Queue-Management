/**
 * Profile Page JavaScript
 * Handles avatar upload, notifications, and UI interactions
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ============================================
    // AVATAR UPLOAD
    // ============================================
    
    window.uploadAvatar = function(input) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            showMessage('Please upload a valid image (JPEG, PNG, JPG, GIF, WebP)', 'error');
            input.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            showMessage('Image size must be less than 2MB', 'error');
            input.value = '';
            return;
        }

        const formData = new FormData();
        formData.append('avatar', file);

        const avatarDiv = document.querySelector('.profile-avatar');
        const originalContent = avatarDiv.innerHTML;
        
        avatarDiv.style.opacity = '0.6';
        avatarDiv.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:32px; color:white;"></i>';

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
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateAvatarDisplay(data.avatar_url);
                showMessage('Avatar updated successfully!', 'success');
            } else {
                showMessage(data.message || 'Error updating avatar', 'error');
                avatarDiv.innerHTML = originalContent;
            }
        })
        .catch(error => {
            console.error('Upload Error:', error);
            showMessage('Error uploading avatar. Please try again.', 'error');
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
    // AVATAR DRAG & DROP
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
    // NOTIFICATION SYSTEM
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
            alertDiv.style.transition = 'opacity 0.4s ease';
            alertDiv.style.opacity = '0';
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 400);
        }, 5000);
    };

    // ============================================
    // COPY EMAIL TO CLIPBOARD
    // ============================================

    const emailElement = document.querySelector('.profile-info .email');
    if (emailElement) {
        emailElement.style.cursor = 'pointer';
        emailElement.title = 'Click to copy email';
        
        emailElement.addEventListener('click', function() {
            const email = this.textContent.trim();
            
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(email).then(() => {
                    showMessage('Email copied to clipboard!', 'success');
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
                showMessage('Email copied to clipboard!', 'success');
            } catch (err) {
                showMessage('Unable to copy email', 'error');
            }
            textArea.remove();
        }
    }

    // ============================================
    // KEYBOARD SHORTCUTS
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

    console.log('✅ Profile page loaded successfully');
    console.log('📋 Tips:');
    console.log('  • Press "E" to edit profile');
    console.log('  • Click email to copy to clipboard');
    console.log('  • Drag & drop image to change avatar');
});