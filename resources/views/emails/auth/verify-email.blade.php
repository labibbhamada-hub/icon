<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>Verify Your Email Address | BHAMADA ICON 2026</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f6f4; color:#26332d; font-family:Arial, Helvetica, sans-serif;">
    <div style="display:none; max-height:0; overflow:hidden; opacity:0; color:transparent;">
        Confirm your email address to complete your BHAMADA ICON 2026 account registration.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; background-color:#f3f6f4;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:600px; background-color:#ffffff; border:1px solid #e1e8e3; border-collapse:separate;">
                    <tr>
                        <td align="center" style="padding:28px 24px 24px; border-top:5px solid #08734f;">
                            <img src="{{ $logoUrl }}" width="92" alt="BHAMADA ICON logo" style="display:block; width:92px; max-width:100%; height:auto; margin:0 auto 14px; border:0; outline:none; text-decoration:none;">
                            <div style="font-size:21px; line-height:1.35; font-weight:700; letter-spacing:0.3px; color:#14533e;">
                                BHAMADA ICON 2026
                            </div>
                            <div style="margin-top:6px; font-size:11px; line-height:1.5; letter-spacing:1.2px; color:#68776e;">
                                OFFICIAL CONFERENCE REGISTRATION
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:3px; background-color:#f2c516; font-size:0; line-height:0;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="padding:32px 32px 12px;">
                            <h1 style="margin:0 0 22px; font-size:24px; line-height:1.35; font-weight:700; color:#24362d;">
                                Verify Your Email Address
                            </h1>

                            <p style="margin:0 0 16px; font-size:15px; line-height:1.75; color:#37463d;">
                                Dear {{ $user->name ?: 'User' }},
                            </p>

                            <p style="margin:0 0 16px; font-size:15px; line-height:1.75; color:#37463d;">
                                Thank you for registering for BHAMADA ICON 2026.
                            </p>

                            <p style="margin:0 0 24px; font-size:15px; line-height:1.75; color:#37463d;">
                                To complete your account registration, please confirm that this email address belongs to you by selecting the button below.
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin:0 auto 24px;">
                                <tr>
                                    <td align="center" bgcolor="#08734f" style="background-color:#08734f;">
                                        <a href="{{ $verificationUrl }}" style="display:inline-block; padding:14px 26px; border:1px solid #08734f; color:#ffffff; font-size:14px; line-height:1.2; font-weight:700; text-decoration:none;">
                                            Verify Email Address
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 18px; font-size:13px; line-height:1.7; color:#58665e;">
                                This verification link is unique to your account and is time-limited. For your security, do not share it with anyone.
                            </p>

                            <p style="margin:0 0 8px; font-size:13px; line-height:1.7; color:#58665e;">
                                If the button does not work, copy and paste the following URL into your browser:
                            </p>
                            <p style="margin:0 0 24px; font-size:12px; line-height:1.7; overflow-wrap:anywhere; word-break:break-word;">
                                <a href="{{ $verificationUrl }}" style="color:#08734f; text-decoration:underline; overflow-wrap:anywhere; word-break:break-word;">{{ $verificationUrl }}</a>
                            </p>

                            <p style="margin:0 0 16px; font-size:13px; line-height:1.7; color:#58665e;">
                                If you did not request an account, no action is required. You may safely disregard this email.
                            </p>

                            <p style="margin:24px 0 0; font-size:14px; line-height:1.7; color:#37463d;">
                                Sincerely,<br>
                                <strong style="color:#14533e;">BHAMADA ICON 2026 Secretariat</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 24px; border-top:1px solid #e5ebe7; background-color:#f8faf8; text-align:center;">
                            <p style="margin:0 0 5px; font-size:12px; line-height:1.6; color:#68776e;">
                                BHAMADA ICON 2026 | Official Conference Registration
                            </p>
                            <p style="margin:0; font-size:11px; line-height:1.6; color:#87938b;">
                                &copy; {{ date('Y') }} BHAMADA ICON 2026. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
