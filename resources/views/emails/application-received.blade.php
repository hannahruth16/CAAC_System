
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CAAC Membership Application Received</title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <div style="max-width: 650px; margin: auto; padding: 25px;">

        <h2 style="color: #245c32;">
            CAAC Membership Application Received
        </h2>

        <p>Dear {{ ucfirst($applicantName) }},</p>

        <p>
            Thank you for submitting your membership application to the
            <strong>Cavaliers Agarwood Agriculture Cooperative (CAAC)</strong>.
            We have successfully logged your submission under the following
            reference number: 
            <strong style="color: #245c32; font-size: 18px;">
                {{ $registration->registration_no }}
            </strong>
        </p>

        <div style="
            background-color: #f7fff9;
            border-left: 4px solid #245c32;
            padding: 15px;
            margin: 20px 0;
        ">
            <strong><h3>Application Summary</h3></strong><br>

            <p>
                <strong>Applicant Name:</strong>
                {{ ucfirst($applicantName) }}
            </p>

            <p>
                <strong>Recommending Regular Member:</strong>
                {{ $recommenderName }}
            </p>
        </div>

        <h3>What Happens Next?</h3>

        <ol>
            <li>
                The Membership Committee, chaired by Ms. Juana Dela Cruz,
                will verify your submitted details and recommender information.
            </li>

            <li>
                Your application will be endorsed to the CAAC Board of
                Directors for formal evaluation and approval.
            </li>
        </ol>

        <p>
            You will receive an update via email once the Board acts on
            your application.
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
            This is an automated email. Please keep your reference number
            for future inquiries regarding your membership application.
        </small>

    </div>

</body>
</html>