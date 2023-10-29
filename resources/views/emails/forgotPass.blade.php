<div style="background-color: #f3f3f3;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 2%">
        <tr>
            <td align="center" bgcolor="#f3f3f3">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                    <p style="margin: 0;">
                        Hi {{ ucwords($user->fullname) }},
                        <br/><br/>
                        Looks like you'd like to change your {{ config('app.name') }} password. Please click the following button to do so.
                        <br/><br/>
                        Please disregard this e-mail if you did not request a password reset.
                        
                        <div align="center" style="margin-top: 5%;">
                            <a href="{{ route('reset-password', ['token' => $resetToken ]) }}" style="background: #b61c1e; color: #fff; text-decoration: none; padding: 10px 30px 10px 30px;">
                                <b>Reset Password</b>
                            </a>
                        </div>

                        <div style="border-top: 1px solid #e8e5ef; margin-top: 25px; margin-bottom: 4%">
                            <div style="margin-top: 2%">
                                If you’re having trouble clicking the "<strong>Reset Password</strong>" button, copy and paste the URL below into your web browser: <br>
                                <a href="{{ route('reset-password', ['token' => $resetToken ]) }}" style="color: #b61c1e; text-decoration: none;">{{ route('reset-password', ['token' => $resetToken ]) }}</a>
                            </div>
                        </div>
                        
                        Cheers, <br/>
                        {{ config('app.name') }} Team
                    </p>
                </td>
                </tr>
            </table>
            </td>
        </tr>
    </table>
</div>