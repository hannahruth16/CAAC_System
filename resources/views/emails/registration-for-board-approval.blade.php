
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CAAC Application For Board Approval</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <div style="max-width: 650px; margin: auto; padding: 25px;">

        <h2 style="color: #245c32;">
            Membership Application Endorsed for Board Approval
        </h2>

        <p>Dear {{ ucfirst($applicantName) }},</p>

        <p>
            Thank you for your interest in becoming a member of the
            <strong>Cavaliers Agarwood Agriculture Cooperative (CAAC).</strong>
        </p>

        <p>
            We are pleased to inform you that your membership application
            has successfully passed the initial review conducted by the
            Membership Committee and has been endorsed for
            <strong>Board Approval.</strong>
        </p>

        <div style="
            background-color: #eaf5ec;
            border-left: 4px solid #245c32;
            padding: 15px;
            margin: 20px 0;
        ">
            <strong>Application Reference Number:</strong><br>

            <span style="font-size: 18px; color: #245c32;">
                {{ $registration->registration_no }}
            </span>
        </div>

        <h3 style="color: #245c32;">
            Current Application Status
        </h3>

        <div style="
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        ">
            <strong style="font-size: 18px; color: #245c32;">
                FOR BOARD APPROVAL
            </strong>
        </div>

        <h3>What Happens Next?</h3>

        <ol>
            <li>
                Your application will be forwarded to the Board
                for final evaluation and consideration.
            </li>

            <li>
                The Board will review your membership application
                and determine whether it can be approved.
            </li>

            <li>
                You will receive another email notification once
                the Board has made its final decision.
            </li>
        </ol>

        <p>
            At this stage, no further action is required from you.
            Please regularly check your registered email address
            for updates regarding your membership application.
        </p>

        <p>
            We appreciate your interest in joining our cooperative
            and thank you for your patience throughout the evaluation
            process.
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