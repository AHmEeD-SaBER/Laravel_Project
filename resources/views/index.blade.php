@extends('master')


@section('content')
    <div class="w-50 mx-auto mt-5 mb-5 bg-light p-4 rounded shadow">
        <form id="registrationForm" method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data" class="form-group">
            @csrf
            <h2 class="text-black fst-italic fw-semibold form-head">User Registration</h2>

            <div id="error-container" class="error-container" style="display: none;"></div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Ahmed Saber" required>
                <label for="full_name">Full Name</label>
                <div class="validation-feedback" id="full_name-feedback"></div>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Ahmed" required>
                <label for="user_name">Username</label>
                <div class="validation-feedback" id="user_name-feedback"></div>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="email" id="email" name="email" placeholder="Ahmed1@gmail.com" required>
                <label for="email">Email</label>
                <div class="validation-feedback" id="email-feedback"></div>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="tel" id="phone" name="phone" placeholder="01127447947" required>
                <label for="phone">Phone Number</label>
                <div class="validation-feedback" id="phone-feedback"></div>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="tel" id="whatsapp" name="whatsapp" placeholder="01127447947" required>
                <label for="whatsapp">WhatsApp Number</label>
                <div class="validation-feedback" id="whatsapp-feedback"></div>
            </div>

            <div class="form-floating w-100 mb-3">
                <textarea id="address" name="address" class="w-100 h-25 mb-3 form-control" placeholder="Address"
                    required></textarea>
                <label for="address">Address</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="password" id="password" name="password" placeholder="********" required>
                <label for="password">Password</label>
                <div class="validation-feedback" id="password-feedback"></div>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="password" id="confirm_password" name="confirm_password"
                    placeholder="********" required>
                <label for="confirm_password">Confirm Password</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" type="file" id="user_image" name="user_image" placeholder="User Image"
                    accept="image/*">
                <label for="user_image">Profile Image</label>

            </div>

            <button type="submit" class="btn-register">Register</button>
        </form>
    </div>    
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    let hasValidationError = false;
    const submitBtn = document.querySelector('.btn-register');
    const validationStates = {};

    // Client-side format validation functions
    function validateFullName(name) {
        return /^[a-zA-Z\s]{3,50}$/.test(name);
    }

    function validateUsername(username) {
        return /^[a-zA-Z0-9_]{4,20}$/.test(username);
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function validatePhoneNumber(phone) {
        return /^[0-9]{11}$/.test(phone);
    }

    function validatePassword(password) {
        return /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/.test(password);
    }

    const fieldsToValidate = [{
            id: 'full_name',
            type: 'fullname',
            validate: validateFullName,
            needsServer: false,
            message: {
                valid: 'Valid name',
                invalid: 'Use 3-50 alphabetic characters'
            }
        },
        {
            id: 'user_name',
            type: 'username',
            validate: validateUsername,
            needsServer: true,
            message: {
                valid: 'Username available',
                invalid: 'Use 4-20 alphanumeric characters'
            }
        },
        {
            id: 'email',
            type: 'email',
            validate: validateEmail,
            needsServer: true,
            message: {
                valid: 'Email available',
                invalid: 'Invalid email format'
            }
        },
        {
            id: 'phone',
            type: 'phone',
            validate: validatePhoneNumber,
            needsServer: true,
            message: {
                valid: 'Phone number available',
                invalid: 'Use 11 digits'
            }
        },
        {
            id: 'password',
            type: 'password',
            validate: validatePassword,
            needsServer: false,
            message: {
                valid: 'Valid password',
                invalid: 'Must have 8+ chars, numbers and special chars'
            }
        },
        {
            id: 'whatsapp',
            type: 'whatsapp',
            validate: validatePhoneNumber,
            needsServer: true,
            message: {
                valid: 'Valid WhatsApp number',
                invalid: 'Use 11 digits'
            }
        }
    ];

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function validateField(fieldId, fieldType, value, field) {
        // First do client-side validation
        const isFormatValid = field.validate(value);
        if (!isFormatValid) {
            updateFieldStatus(fieldId, {
                valid: false,
                message: field.message.invalid
            });
            return;
        }

        // Only make server request if needed and format is valid
        if (field.needsServer) {
            const formData = new FormData();
            formData.append('field', fieldId);
            formData.append('type', fieldType);
            formData.append('value', value);

            fetch('{{ route("validate.field") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                updateFieldStatus(fieldId, data);
                validationStates[fieldId] = data.valid;
                hasValidationError = Object.values(validationStates).some(state => state === false);
                submitBtn.disabled = hasValidationError;
            })
            .catch(error => {
                console.error('Validation Error:', error);
            });
        } else {
            // For non-server validation, update immediately
            updateFieldStatus(fieldId, {
                valid: true,
                message: field.message.valid
            });
            validationStates[fieldId] = true;
            hasValidationError = Object.values(validationStates).some(state => state === false);
            submitBtn.disabled = hasValidationError;
        }
    }

    function updateFieldStatus(fieldId, data) {
        const inputElement = document.getElementById(fieldId);
        const feedbackDiv = document.getElementById(`${fieldId}-feedback`);

        if (feedbackDiv && inputElement) {
            feedbackDiv.textContent = data.message;
            feedbackDiv.className = `validation-feedback ${data.valid ? 'valid' : 'invalid'}`;
            inputElement.classList.remove('is-valid', 'is-invalid');
            inputElement.classList.add(data.valid ? 'is-valid' : 'is-invalid');
        }
    }

    const debouncedValidation = debounce((fieldId, fieldType, value, field) => {
        validateField(fieldId, fieldType, value, field);
    }, 500);

    fieldsToValidate.forEach(field => {
        const input = document.getElementById(field.id);
        if (input) {
            input.addEventListener('input', (e) => {
                debouncedValidation(field.id, field.type, e.target.value, field);
            });
        }
    });

    // Password confirmation validation
    const confirmPassword = document.getElementById('confirm_password');
    const password = document.getElementById('password');

    if (confirmPassword && password) {
        confirmPassword.addEventListener('input', function() {
            const feedbackDiv = document.getElementById('confirm_password-feedback') ||
                createFeedbackDiv('confirm_password');

            const isValid = this.value === password.value;
            updateFieldStatus('confirm_password', {
                valid: isValid,
                message: isValid ? 'Passwords match' : 'Passwords do not match'
            });
            validationStates['confirm_password'] = isValid;
            hasValidationError = Object.values(validationStates).some(state => state === false);
            submitBtn.disabled = hasValidationError;
        });
    }

    // Form submission
    const form = document.getElementById('registrationForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            
            fetch('{{ route("register.submit") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    form.innerHTML = '<div class="success-message">Registration Successful!</div>';
                } else {
                    const errorContainer = document.getElementById('error-container');
                    errorContainer.style.display = 'block';
                    errorContainer.innerHTML = data.errors.map(error => 
                        `<p class="error-message">${error}</p>`
                    ).join('');
                }
            })
            .catch(error => {
                console.error('Submission Error:', error);
                const errorContainer = document.getElementById('error-container');
                errorContainer.style.display = 'block';
                errorContainer.innerHTML = '<p class="error-message">An error occurred. Please try again.</p>';
            });
        });
    }

    function createFeedbackDiv(fieldId) {
        const div = document.createElement('div');
        div.id = `${fieldId}-feedback`;
        div.className = 'validation-feedback';
        document.getElementById(fieldId).parentNode.appendChild(div);
        return div;
    }

    // File validation
    const userImage = document.getElementById('user_image');
    if (userImage) {
        userImage.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    updateFieldStatus('user_image', {
                        valid: false,
                        message: 'File size must be less than 5MB'
                    });
                    this.value = '';
                    return;
                }

                if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
                    updateFieldStatus('user_image', {
                        valid: false,
                        message: 'Please upload an image file (JPEG, PNG, or GIF)'
                    });
                    this.value = '';
                    return;
                }

                updateFieldStatus('user_image', {
                    valid: true,
                    message: 'Valid image selected'
                });
            }
        });
    }
});
</script>
























<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

/* Global styles */
* {
    font-family: 'Poppins';
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}


body {
    background-color: #f5f5f5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Registration container */
.registration-container {
    max-width: 600px;
    margin: 2rem auto;
    padding: 2rem;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

/* Form header */
h2 {
    text-align: center;
    color: #2563eb;
    margin-bottom: 2rem;
}


.form-group input:focus,
.form-group textarea:focus {
    outline: none !important;
    border-color: #000 !important;
    box-shadow: 0 0 0 2px rgb(0, 0, 0) !important;
}

/* Validation feedback */
.validation-feedback {
    font-size: 0.875rem;
    margin-top: 0.5rem;
    min-height: 20px;
}

.validation-feedback.valid {
    color: #059669;
}

.validation-feedback.invalid {
    color: #dc2626;
}

/* Input states */
.form-group input.is-valid {
    border-color: #059669;
    background-color: #f0fdf4;
}

.form-group input.is-invalid {
    border-color: #dc2626;
    background-color: #fef2f2;
}

/* Error container */
.error-container {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    color: #dc2626;
}

/* Success message */
.success-message {
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
    color: #059669;
    font-weight: 500;
}

/* Register button */
.btn-register {
    width: 100%;
    padding: .75rem 1.5rem;
    background-color: #808080;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-register:hover {
    background-color: #000;
}

.btn-register:focus {
    outline: none;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
}

/* File input */
input[type="file"] {
    padding: 0.5rem;
    border: 2px dashed #d0d5dd;
}

/* Responsive design */
@media (max-width: 640px) {
    .registration-container {
        margin: 1rem;
        padding: 1rem;
    }
}
</style>