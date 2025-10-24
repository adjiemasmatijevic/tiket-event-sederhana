<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0; padding:0; background-color:#f5f6fa; font-family: Arial, sans-serif;">

    <!-- Container -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f5f6fa; padding:20px 0;">
        <tr>
            <td align="center">

                <!-- Card -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
                    <tr>
                        <td align="center" style="background-color:#fc983c; padding:20px;">
                            <h1 style="margin:0; color:#ffffff; font-size:22px;">Password Reset Request</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px; color:#333333; font-size:15px; line-height:1.6;">
                            <p>Hello,</p>
                            <p>You recently requested to reset your password. Click the button below to proceed:</p>

                            <!-- Button -->
                            <p style="text-align:center; margin:30px 0;">
                                <a href="{{ $link }}" target="_blank"
                                    style="background-color:#fc983c; color:#ffffff; text-decoration:none; padding:12px 24px; border-radius:6px; font-weight:bold; display:inline-block;">
                                    Reset Password
                                </a>
                            </p>

                            <p>If the button above does not work, please copy and paste the following link into your browser:</p>
                            <p style="word-break:break-all; color:#fc983c;">
                                <a href="{{ $link }}" target="_blank" style="color:#fc983c;">{{ $link }}</a>
                            </p>

                            <p><b>Note:</b> This link is valid for 10 minutes only.</p>
                            <p style="margin-top:30px;">Thank you,<br>The Support Team</p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color:#f0f0f5; padding:15px; font-size:12px; color:#666;">
                            &copy; {{ date('Y') }} Your App. All rights reserved.
                        </td>
                    </tr>
                </table>
                <!-- End Card -->

            </td>
        </tr>
    </table>
    <!-- End Container -->

</body>

</html>