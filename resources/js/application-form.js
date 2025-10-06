document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('application-form');
    const fileInput = document.getElementById('files');
    const fileList = document.getElementById('file-list');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');
    const alertContainer = document.getElementById('alert-container');

    let selectedFiles = [];

    // === File select ===
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    // === Drag & Drop ===
    const wrapper = document.querySelector('.file-upload-wrapper');
    const fileUploadLabel = document.querySelector('.file-upload-label');

    ['dragenter', 'dragover'].forEach(evt => {
        fileUploadLabel.addEventListener(evt, (e) => {
            e.preventDefault();
            e.stopPropagation();
            wrapper.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(evt => {
        fileUploadLabel.addEventListener(evt, (e) => {
            e.preventDefault();
            e.stopPropagation();
            wrapper.classList.remove('dragover');
        });
    });

    fileUploadLabel.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // === Handle & validate chosen files ===
    function handleFiles(files) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        Array.from(files).forEach(file => {
            if (!validTypes.includes(file.type)) {
                showError('files', `${file.name} is not a valid file type. Only JPG, PNG, and PDF are allowed.`);
                return;
            }
            if (file.size > maxSize) {
                showError('files', `${file.name} exceeds the 5MB size limit.`);
                return;
            }
            selectedFiles.push(file);
        });

        updateFileList();
        clearError('files');
    }

    // === Render list with thumbnails/badges ===
    function updateFileList() {
        fileList.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const item = document.createElement('div');
            item.className = 'file-item';

            // preview container
            let previewEl;
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.className = 'file-thumb';
                img.src = URL.createObjectURL(file);
                img.onload = () => URL.revokeObjectURL(img.src);
                previewEl = img;
            } else {
                const badge = document.createElement('div');
                badge.className = 'pdf-badge';
                badge.textContent = 'PDF';
                previewEl = badge;
            }

            // meta (name + size)
            const meta = document.createElement('div');
            meta.className = 'file-details';
            const nameEl = document.createElement('span');
            nameEl.className = 'file-name';
            nameEl.textContent = file.name;
            const sizeEl = document.createElement('span');
            sizeEl.className = 'file-size';
            sizeEl.textContent = formatFileSize(file.size);
            meta.appendChild(nameEl);
            meta.appendChild(sizeEl);

            // remove button
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'file-remove';
            removeBtn.setAttribute('aria-label', `Remove ${file.name}`);
            removeBtn.textContent = '✕';
            removeBtn.addEventListener('click', () => {
                selectedFiles.splice(index, 1);
                updateFileList();
            });

            item.appendChild(previewEl);
            item.appendChild(meta);
            item.appendChild(removeBtn);

            fileList.appendChild(item);
        });
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
    }

    // === Submit via AJAX ===
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        clearAllErrors();

        if (selectedFiles.length === 0) {
            showError('files', 'Please upload at least one file.');
            return;
        }

        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoader.style.display = 'inline-block';

        const formData = new FormData(form);

        // replace any native input files with our validated list
        formData.delete('files[]');
        selectedFiles.forEach(file => formData.append('files[]', file));

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(form.action || '/applications', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                form.reset();
                selectedFiles = [];
                updateFileList();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(key => showError(key, data.errors[key][0]));
                } else {
                    showAlert('error', data.message || 'An error occurred.');
                }
            }
        })
        .catch(() => showAlert('error', 'An unexpected error occurred. Please try again.'))
        .finally(() => {
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            btnLoader.style.display = 'none';
        });
    });

    // === Errors & alerts ===
    function showError(field, message) {
        const fieldName = field.replace('[]', '').replace('.', '_');
        const input = document.getElementById(fieldName) || document.querySelector(`[name="${field}"]`);
        const errorSpan = document.getElementById(`error-${fieldName}`);
        if (input) input.classList.add('error');
        if (errorSpan) errorSpan.textContent = message;
    }

    function clearError(field) {
        const fieldName = field.replace('[]', '').replace('.', '_');
        const input = document.getElementById(fieldName) || document.querySelector(`[name="${field}"]`);
        const errorSpan = document.getElementById(`error-${fieldName}`);
        if (input) input.classList.remove('error');
        if (errorSpan) errorSpan.textContent = '';
    }

    function clearAllErrors() {
        document.querySelectorAll('.error-message').forEach(span => { span.textContent = ''; });
        document.querySelectorAll('input.error, select.error, textarea.error').forEach(i => i.classList.remove('error'));
    }

    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alertDiv);
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            alertDiv.style.transform = 'translateY(-10px)';
            setTimeout(() => alertDiv.remove(), 300);
        }, 5000);
    }

    // Real-time field validation (keep)
    form.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('blur', function() {
            if (this.value.trim() !== '') clearError(this.name);
        });
    });
});
