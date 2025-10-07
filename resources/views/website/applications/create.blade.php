@extends('layouts.app')

@section('title', 'Submit Application')

@section('content')
<div class="application-container">
    <div class="application-card">
        <h1>Submit Your Application</h1>
        <p class="subtitle">Please fill out all required fields below</p>

        <div id="alert-container"></div>

        <form id="application-form" method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="contact_email">Contact Email <span class="required">*</span></label>
                    <input 
                        type="email" 
                        id="contact_email" 
                        name="email" 
                        required
                    >
                    <span class="error-message" id="error-contact_email"></span>
                </div>

                <div class="form-group">
                    <label for="contact_phone">Contact Phone Number <span class="required">*</span></label>
                    <input 
                        type="tel" 
                        id="contact_phone" 
                        name="phone" 
                        placeholder="+1234567890"
                        required
                    >
                    <span class="error-message" id="error-contact_phone"></span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="date_of_birth">Date of Birth <span class="required">*</span></label>
                    <input 
                        type="date" 
                        id="date_of_birth" 
                        name="date_of_birth" 
                        required
                    >
                    <span class="error-message" id="error-date_of_birth"></span>
                </div>

                <div class="form-group">
                    <label for="gender">Gender <span class="required">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                    <span class="error-message" id="error-gender"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="country">Country <span class="required">*</span></label>
                <select id="country" name="country" required>
                    <option value="">Select Country</option>
                    <option value="United States">United States</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="Canada">Canada</option>
                    <option value="Australia">Australia</option>
                    <option value="Germany">Germany</option>
                    <option value="France">France</option>
                    <option value="Egypt">Egypt</option>
                    <option value="Saudi Arabia">Saudi Arabia</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="India">India</option>
                    <option value="China">China</option>
                    <option value="Japan">Japan</option>
                    <option value="Brazil">Brazil</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Other">Other</option>
                </select>
                <span class="error-message" id="error-country"></span>
            </div>

            <div class="form-group">
                <label for="files">Upload Files (Images & PDFs) <span class="required">*</span></label>
                <div class="file-upload-wrapper">
                    <input 
                        type="file" 
                        id="files" 
                        name="files[]" 
                        multiple 
                        accept=".jpg,.jpeg,.png,.pdf"
                        required
                    >
                    <label for="files" class="file-upload-label">
                        <span class="file-upload-icon">📁</span>
                        <span class="file-upload-text">Click to select files or drag and drop</span>
                    </label>
                </div>
                <div id="file-list" class="file-list"></div>
                <span class="error-message" id="error-files"></span>
                <small class="form-hint">Accepted formats: JPG, JPEG, PNG, PDF (Max 5MB per file)</small>
            </div>

            <div class="form-group">
                <label for="comments">Comments</label>
                <textarea 
                    id="comments" 
                    name="comments" 
                    rows="5"
                    placeholder="Enter any additional comments or information..."
                ></textarea>
                <span class="error-message" id="error-comments"></span>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large" id="submit-btn">
                    <span class="btn-text">Submit Application</span>
                    <span class="btn-loader" style="display: none;">Submitting...</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@vite('resources/js/application-form.js')
