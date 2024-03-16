<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
    <style>
        /* Styles for improved layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .signature {
            margin-top: 20px;
            font-style: italic;
            color: #777;
        }
        .contact-info {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hello!</h1>
        <p>This is to inform you about some recent activities:</p>
        
        @if ($contract_id)
        <p>Contract ID: {{ $contract_id }} has been processed</p>
        @elseif ($msa_id)
        <p>MSA ID: {{ $msa_id }} has been processed.</p>
        @endif
        @if ($action)
            <p>{{ ucfirst($action) }} action has been taken by</p>
        @endif
        @if ($username)
            <p>{{ ucfirst($username) }}</p>
        @endif

        <!-- Signature -->
        <div class="signature">
            <p><strong>CONTRACK</strong></p>
            <p>EXPERION TECHNOLOGIES</p>
            <div class="contact-info">
                <p>P: <a href="tel:+917510552095">+91 7510552095</a></p>
                <p>E: <a href="mailto:contrack@experionglobal.com">contrack@experionglobal.com</a></p>
                <p>W: <a href="https://experionglobal.com">experionglobal.com</a></p>
            </div>
        </div>
    </div>
</body>
</html>
