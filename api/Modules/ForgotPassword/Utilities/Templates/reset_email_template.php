<?php
$year = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f6f7; margin: 0; padding: 0;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f6f7; padding: 30px 0;">
    <tr>
      <td align="center">
        <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 6px; overflow: hidden;">
          <tr style="background-color: #0066cc;">
            <td style="padding: 20px 40px; color: #ffffff; font-size: 24px; font-weight: bold;">
              <center><img src="https://gillanesolutions.net/haris/wp-content/uploads/2025/07/logo-cw.png" height="100"></center>
            </td>
          </tr>
          <tr>
            <td style="padding: 30px 40px;">
              <h2 style="margin-top: 0; font-size: 20px; color: #333;">Reset Your Password</h2>
              <p style="font-size: 14px; color: #555;">
                You recently requested to reset your password for your Clockwise account. Click the button below to reset it.
              </p>
              <p style="text-align: center; margin: 30px 0;">
                <a href="<?= $resetLink ?>" style="background-color: #0066cc; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 4px; display: inline-block; font-weight: bold;">
                  Reset Password
                </a>
              </p>
              <p style="font-size: 13px; color: #888;">
                If you didn’t request this, you can safely ignore this email. The link will expire in 1 hour.
              </p>
              <p style="font-size: 12px; color: #ccc; border-top: 1px solid #eee; margin-top: 30px; padding-top: 10px;">
                © <?= $year ?> Clockwise App. All rights reserved.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
