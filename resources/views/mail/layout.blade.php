<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $heading }}</title>
</head>
<body style="margin:0;padding:24px 12px;background:#f3f6f1;font-family:Arial,Helvetica,sans-serif;color:#191f30;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:6px;">
        <tr>
            <td style="background:#4a7a2a;color:#ffffff;padding:16px 24px;border-radius:6px 6px 0 0;font-size:18px;font-weight:bold;">
                Just Lite · {{ $heading }}
            </td>
        </tr>
        <tr>
            <td style="padding:20px 24px;font-size:15px;line-height:1.5;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                    @foreach($rows as $label => $value)
                        <tr>
                            <td style="padding:6px 12px 6px 0;color:#4e4e4e;vertical-align:top;white-space:nowrap;">{{ $label }}</td>
                            <td style="padding:6px 0;vertical-align:top;">{!! nl2br(e($value)) !!}</td>
                        </tr>
                    @endforeach
                </table>
                <p style="margin:20px 0 0;color:#4e4e4e;font-size:13px;">Reply to this email to answer {{ $replyName }} directly. It is also saved in the admin.</p>
            </td>
        </tr>
    </table>
</body>
</html>
