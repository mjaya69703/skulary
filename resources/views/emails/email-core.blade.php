<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #f9fafb;
            padding: 20px;
        }

        .email-content {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .email-header h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .email-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .email-body {
            padding: 30px 20px;
        }

        .email-body h2 {
            font-size: 18px;
            margin-bottom: 12px;
            color: #1f2937;
        }

        .email-body p {
            margin-bottom: 16px;
            font-size: 14px;
            line-height: 1.6;
        }

        .email-button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            margin: 20px 0;
            transition: background-color 0.3s;
        }

        .email-button:hover {
            background-color: #5568d3;
        }

        .email-code {
            background-color: #f3f4f6;
            border-left: 4px solid #667eea;
            padding: 12px 16px;
            margin: 16px 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #1f2937;
            word-break: break-all;
        }

        .email-footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }

        .email-footer a {
            color: #667eea;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #6b7280;
            font-size: 13px;
        }

        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            margin: 16px 0;
            color: #92400e;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-content">
            @yield('email-content')

            <div class="email-footer">
                <p>© {{ date('Y') }} Skulary. All rights reserved.</p>
                <p>If you have questions, please contact <a href="mailto:support@skulary.com">support@skulary.com</a></p>
            </div>
        </div>
    </div>
</body>

</html>
