<div style="background-color: #f3f3f3;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 2%">
        <tr>
            <td align="center" bgcolor="#f3f3f3">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="left" bgcolor="#ffffff"
                            style="padding: 24px; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                            <p style="margin: 0;">
                                Hi {{ ucwords($user->fullname) }},
                                <br /><br />
                                Thank you for registering with {{ config('app.name') }}! We're thrilled to have you on
                                board and always ready to serve you better.
                                <br><br>
                                To complete your account setup, please verify your email address by clicking the link
                                below to start enjoying our amazing discount and offers
                                <br><br>

                            <div align="center" style="margin-top: 5%;">
                                <a href="{{ route('verify-account', ['code' => $verifyCode]) }}"
                                    style="background: #b61c1e; color: #fff; text-decoration: none; padding: 10px 30px 10px 30px;">
                                    <b>Verify Account</b>
                                </a>
                            </div>

                            <div style="border-top: 1px solid #e8e5ef; margin-top: 25px; margin-bottom: 4%">
                                <div style="margin-top: 2%">
                                    If you’re having trouble clicking the "<strong>Verify Account</strong>" link, copy
                                    and paste the URL below into your web browser: <br>
                                    <a href="{{ route('verify-account', ['code' => $verifyCode]) }}"
                                        style="color: #b61c1e; text-decoration: none;">{{ route('verify-account', ['code' => $verifyCode]) }}</a>
                                </div>
                            </div>

                            <br><br>
                            If you didn't sign up for an account, please ignore this email
                            <br><br>
                            Need assistance or have questions? Our customer support team is here to help always.
                            <br><br>

                            Cheers, <br />
                            {{ config('app.name') }} Team
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
