<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
        }

        .info-row {
            margin-bottom: 15px;
            padding: 10px;
            background-color: white;
            border-left: 3px solid #4F46E5;
        }

        .label {
            font-weight: bold;
            color: #4F46E5;
            display: inline-block;
            width: 150px;
        }

        .value {
            color: #333;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>

<body>
    @php
        use Carbon\Carbon;

        if (is_array($user ?? null)) {
            $user = (object) $user;
        }
        if (is_array($application ?? null)) {
            $application = (object) $application;
        }
    @endphp
    <div class="header">
        <h1>New Application Submitted</h1>
    </div>

    <div class="content">
        <p>A new application has been submitted. Please find the details below:</p>

        <div class="info-row">
            <span class="label">Applicant Name:</span>
            <span class="value">{{ $user->name }}</span>
        </div>

        <div class="info-row">
            <span class="label">User Email:</span>
            <span class="value">{{ $user->email }}</span>
        </div>

        <div class="info-row">
            <span class="label">Contact Phone:</span>
            <span class="value">{{ $application->phone }}</span>
        </div>

        <div class="info-row">
            <span class="label">Date of Birth:</span>
            <span
                class="value">{{ optional(Carbon::parse($application->date_of_birth ?? null))->format('F d, Y') }}</span>
        </div>

        <div class="info-row">
            <span class="label">Gender:</span>
            <span class="value">{{ ucfirst($application->gender) }}</span>
        </div>

        <div class="info-row">
            <span class="label">Country:</span>
            <span class="value">{{ $application->country }}</span>
        </div>

        @if ($application->comments)
            <div class="info-row">
                <span class="label">Comments:</span>
                <span class="value">{{ $application->comments }}</span>
            </div>
        @endif

        <div class="info-row">
            <span class="label">Submitted On:</span>
            <span
                class="value">{{ optional(Carbon::parse($application->created_at ?? null))->format('F d, Y \a\t h:i A') }}</span>
        </div>

        <p style="margin-top: 20px;"><strong>Note:</strong> All uploaded files and a detailed PDF summary are attached
            to this email.</p>
    </div>

    <div class="footer">
        <p>This is an automated email from Application System.</p>
    </div>
</body>

</html>
