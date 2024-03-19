<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
    <style>
        /* Styles from the first HTML */
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            -webkit-text-size-adjust: none;
            text-size-adjust: none;
        }
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: inherit !important;
        }
        #MessageViewBody a {
            color: inherit;
            text-decoration: none;
        }
        p {
            line-height: inherit
        }
        .desktop_hide,
        .desktop_hide table {
            mso-hide: all;
            display: none;
            max-height: 0px;
            overflow: hidden;
        }
        .image_block img+div {
            display: none;
        }
        @media (max-width:620px) {
            .desktop_hide table.icons-inner {
                display: inline-block !important;
            }
            .icons-inner {
                text-align: center;
            }
            .icons-inner td {
                margin: 0 auto;
            }
            .mobile_hide {
                display: none;
            }
            .row-content {
                width: 100% !important;
            }
            .stack .column {
                width: 100%;
                display: block;
            }
            .mobile_hide {
                min-height: 0;
                max-height: 0;
                max-width: 0;
                overflow: hidden;
                font-size: 0px;
            }
            .desktop_hide,
            .desktop_hide table {
                display: table !important;
                max-height: none !important;
            }
        }
    </style>
</head>
<body style="background-color: #ffffff; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
    Your content from the second file
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
 

        Signature
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
    End of your content
</body>
</html>