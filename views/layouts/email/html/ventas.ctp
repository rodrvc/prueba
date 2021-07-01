<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn\'t be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <!-- Web Font / @font-face : BEGIN -->
    <!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. -->

    <!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. -->
    <!--[if mso]>
    <style>
        * {
            font-family: sans-serif !important;
        }
    </style>
    <![endif]-->

    <!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ -->
    <!--[if !mso]><!-->
    <!-- insert web font reference, eg: <link href=\'https://fonts.googleapis.com/css?family=Roboto:400,700\' rel=\'stylesheet\' type=\'text/css\'> -->
    <!--<![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background: rgb(1,59,133);
            background: linear-gradient(0deg, rgba(1,59,133,1) 0%, rgba(1,59,133,1) 35%, rgba(233,236,229,1) 36%, rgba(233,236,229,1) 100%);
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        table table table {
            table-layout: auto;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode:bicubic;
        }

        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],  /* iOS */
        .x-gmail-data-detectors,    /* Gmail */
        .x-gmail-data-detectors *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* What it does: Prevents Gmail from displaying an download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }
        /* If the above doesn\'t work, add a .g-img class to any image in question. */
        img.g-img + div {
            display: none !important;
        }

        /* What it does: Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you\'d like to fix */
        /* Thanks to Eric Lepetit (@ericlepetitsf) for help troubleshooting */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) { /* iPhone 6 and 6+ */
            .email-container {
                min-width: 375px !important;
            }
        }

        @media screen and (max-width: 480px) {
            /* What it does: Forces Gmail app to display email full width */
            u ~ div .email-container {
                min-width: 100vw;
            }
        }

    </style>
    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style>

        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }
        .button-td:hover,
        .button-a:hover {
            background: #555555 !important;
            border-color: #555555 !important;
        }

        /* Media Queries */
        @media screen and (max-width: 600px) {

            /* What it does: Adjust typography on small screens to improve readability */
            .email-container p {
                font-size: 17px !important;
            }

        }

    </style>
    <!-- Progressive Enhancements : END -->

    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. -->
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

</head>
<body width="100%" bgcolor="#222222" style="margin: 0; mso-line-height-rule: exactly; overflow-x: ">
<center style="width: 100%; text-align: left;background: rgb(1,59,133);background: linear-gradient(0deg, rgba(1,59,133,1) 0%, rgba(1,59,133,1) 35%, rgba(233,236,229,1) 35%, rgba(233,236,229,1) 100%); ">

    <!-- Visually Hidden Preheader Text : BEGIN -->
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        Skechers
    </div>
    <!-- Visually Hidden Preheader Text : END -->

    <!--
        Set the email width. Defined in two places:
        1. max-width for all clients except Desktop Windows Outlook, allowing the email to squish on narrow but never go wider than 600px.
        2. MSO tags for Desktop Windows Outlook enforce a 600px width.
    -->
    <div style="max-width: 600px; margin: auto;" class="email-container">
        <table role="presentation" bgcolor="#0e3c87" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 600px;">
            <tr>
                <td><a href="https://www.skechers.cl/" title="https://www.skechers.cl/" target="_blank"><img src="https://i.ibb.co/kykTG25/banner-up.jpg" width="100%" alt="alt_text" border="0"></a>
                </td>
            </tr>
        </table>
<?php echo $content_for_layout;?>
        <tr>
            <td style="text-align: center"><a href="https://www.skechers.cl/" title="https://www.skechers.cl/" target="_blank"><img src="https://i.ibb.co/X2BMTD9/logo-footer.png" width="200" height="50" alt="alt_text" border="0"></a>

            </td>

        </tr>



        <!-- Two Even Columns : END -->

        <!-- Clear Spacer : BEGIN -->
        <tr>
            <td aria-hidden="true" height="40" style="font-size: 0; line-height: 0;">&nbsp;

            </td>
        </tr>
        <!-- Clear Spacer : END -->

        <!-- 1 Column Text : BEGIN -->
        <tr>
            <td bgcolor="#0e3c87">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="font-family: Lucida Grande, Lucida Sans Unicode, Lucida Sans, DejaVu Sans, Verdana,' sans-serif'; text-align: center; color: #666666; font-size: 12px;">
                    <tr>
                        <td align="center" valign="middle" height="80" width="50%" style="display: inline-block; text-align: center; color: #ffffff;  border-top: 20px solid #033c87">
                            <a href="https://www.facebook.com/skechers.chile/" target="_blank" title="https://www.facebook.com/skechers.chile/" style="color: #fff"><img src="https://i.ibb.co/64J4ZCW/facebook.png" style="display: inline-block" width="50" alt="alt_text" border="0">
                                <br>
                                skecherschile</a>
                        </td>
                        <td align="center" valign="middle" height="80" width="50%" style="display: inline-block; text-align: center; color: #ffffff;  border-top: 20px solid #033c87">
                            <a href="https://www.instagram.com/skecherschile/" target="_blank" title="https://www.instagram.com/skecherschile/" style="color: #fff"><img src="https://i.ibb.co/RYxNw1Z/instagram.png" style="display: inline-block" width="50" alt="alt_text" border="0">
                                <br>
                                skecherschile</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- 1 Column Text : END -->

        </table>
        <!-- Email Body : END -->

        <!-- Email Footer : BEGIN -->

        <!-- Email Footer : END -->

        <!--[if mso]>
        </td>
        </tr>
        </table>
        <![endif]-->
    </div>



</center>
</body>
</html>

