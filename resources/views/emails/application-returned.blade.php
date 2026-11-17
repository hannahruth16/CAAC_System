
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CAAC Application Returned</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <div style="max-width: 650px; margin: auto; padding: 25px;">

        <h2 style="color: #245c32;">
            Membership Application Returned for Correction
        </h2>

        <p>Dear {{ ucfirst($applicantName) }},</p>

        <p>
            Thank you for your interest in becoming a member of the
            <strong>Cavaliers Agarwood Agriculture Cooperative (CAAC).</strong>
        </p>

        <p>
            After reviewing your membership application, the Membership
            Committee has determined that some information or documents
            require correction or further clarification before your
            application can proceed to the next stage.
        </p>

        <div style="
            background-color: #fff8e7;
            border-left: 4px solid #d69e2e;
            padding: 15px;
            margin: 20px 0;
        ">
            <strong>Application Reference Number:</strong><br>

            <span style="font-size: 18px; color: #8a5a00;">
                {{ $registration->registration_no }}
            </span>
        </div>

        <h3 style="color: #245c32;">
            Reason for Return / Required Corrections
        </h3>

        <div style="
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        ">
            {{ $registration->remarks }}
        </div>

        <h3>What Should You Do Next?</h3>

        <ol>
            <li>
                Review the remarks and corrections indicated above.
            </li>

            <li>
                Access your CAAC membership account using the link below.
            </li>

            <li>
                Update the necessary information and/or documents
                based on the committee's remarks.
            </li>

            <li>
                Review your corrections and resubmit your application
                for another evaluation.
            </li>
        </ol>

        <div style="text-align: center; margin: 30px 0;">

            <a href="{{ $resubmitUrl }}"
               style="
                    background-color: #245c32;
                    color: #ffffff;
                    padding: 14px 25px;
                    text-decoration: none;
                    border-radius: 5px;
                    display: inline-block;
                    font-weight: bold;
               ">
                ACCESS AND RESUBMIT APPLICATION
            </a>

        </div>

        <p>
            Please ensure that all required corrections have been addressed
            before resubmitting your application.
        </p>

        <p>
            Thank you for your cooperation and understanding.
        </p>

        <p style="margin-top: 30px;">
            <strong>
                One Cooperative. One Mission.
                Together We Grow, Together We Prosper.
            </strong>
        </p>

        <p>
            Warm regards,<br>
            <strong>CAAC Secretariat</strong><br>
            Cavaliers Agarwood Agriculture Cooperative (CAAC)
        </p>

        <p>
            Email:
            <a href="mailto:cavaliersagarwoods2024@gmail.com">
                cavaliersagarwoods2024@gmail.com
            </a>
            <br>
            Mobile: 0999-999-9999
        </p>

        <hr>

        <small style="color: #777;">
            This is an automated notification from the CAAC Membership
            Management System. Please keep your application reference
            number for future inquiries.
        </small>

    </div>

</body>
</html>