<!DOCTYPE html>
@php
    use Carbon\Carbon;

    if (is_array($user ?? null)) {
        $user = (object) $user;
    }
    if (is_array($application ?? null)) {
        $application = (object) $application;
    }
@endphp
<html>

<head>
    <meta charset="utf-8">
    <title>Application Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
        }

        .header h1 {
            color: #4F46E5;
            margin: 0;
        }

        .header p {
            color: #666;
            margin: 5px 0;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background-color: #4F46E5;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .info-table td:first-child {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 35%;
        }

        .files-list {
            list-style: none;
            padding: 0;
        }

        .files-list li {
            padding: 8px;
            background-color: #f9f9f9;
            margin-bottom: 5px;
            border-left: 3px solid #4F46E5;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Application Details</h1>
        {{ optional(Carbon::parse($application->created_at ?? null))->format('F d, Y \a\t h:i A') }}
    </div>

    <div class="section">
        <div class="section-title">Applicant Information</div>
        <table class="info-table">
            <tr>
                <td>Full Name</td>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <td>User Email</td>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <td>Member Since</td>
                <td>{{ optional(Carbon::parse($user->created_at ?? null))->format('F d, Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Contact Information</div>
        <table class="info-table">
            <tr>
                <td>Contact Email</td>
                <td>{{ $application->email }}</td>
            </tr>
            <tr>
                <td>Contact Phone Number</td>
                <td>{{ $application->phone }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Personal Information</div>
        <table class="info-table">
            <tr>
                <td>Date of Birth</td>
                <td>{{ optional(Carbon::parse($application->date_of_birth ?? null))->format('F d, Y') }}</td>

            </tr>
            <tr>
                <td>Age</td>
                <td>
                    @php $dob = isset($application->date_of_birth) ? Carbon::parse($application->date_of_birth) : null; @endphp
                    {{ $dob ? $dob->age . ' years old' : 'N/A' }}
                </td>

            </tr>
            <tr>
                <td>Gender</td>
                <td>{{ ucfirst($application->gender) }}</td>
            </tr>
            <tr>
                <td>Country</td>
                <td>{{ $application->country }}</td>
            </tr>
        </table>
    </div>

    @if ($application->comments)
        <div class="section">
            <div class="section-title">Comments</div>
            <div style="padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd;">
                {{ $application->comments }}
            </div>
        </div>
    @endif

    @if (!empty($files))
        <div class="section">
            <div class="section-title">Uploaded Files</div>
            <ul class="files-list">
                @foreach ($files as $file)
                    <li>
                        <strong>{{ $file['original_name'] ?? 'file' }}</strong><br>
                        <small>
                            Size: {{ isset($file['size']) ? number_format($file['size'] / 1024, 2) . ' KB' : 'n/a' }}
                            | Type: {{ $file['mime_type'] ?? 'n/a' }}
                        </small>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="footer">
        <p>This document was automatically generated by the Application System.</p>
        <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
    </div>
</body>

</html>
