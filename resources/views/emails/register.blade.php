<!-- This mail will trigger when user register verify his email with the link clicked -->
<!DOCTYPE html">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>SHEconomy</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <style type="text/css">
    a[x-apple-data-detectors] {color: inherit !important;}
  </style>
  <style>
      .button{
        background-color: #ed4c67;
        border: none;
        color: white;
        padding: 5px 100px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 5px;
      }
  </style>

</head>
<body style="margin: 0; padding: 0;">
  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td style="padding: 20px 0 30px 0;">

        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse; border: 1px solid #cccccc;">
            <tr>
                <td>
                    <img style="display: block;
                    margin-left: auto;
                    margin-right: auto; margin-top: 15px;" src="https://sheconomy.in/public/uploads/logo/nsDrluMIaWzxXm50ZxtOXaAJAp3AXfQLATMeh5CK.png">
                </td>
            </tr>
        <tr>
            <!-- <td align="center"style="padding: 40px 0 30px 0;">
            <img src="https://www.talkwalker.com/images/2020/blog-headers/image-analysis.png" alt="Creating Email Magic." width="450" height="300" style="display: block;" />
            </td>
        </tr> -->
        <tr>
            <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                <td style="color: #153643; font-family: Arial, sans-serif;">
                    <h1 style="font-size: 24px; margin: 0;">Thank You For Registering On SHEconomy</h1>
                </td>
                </tr>
                <tr>
                <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 20px 0 30px 0;">
                    <p style="margin: 0;">Hi, {{ $user->name }}, </p>
                    <p style="margin: 0;">Welcome to SHEconomy.in</p>
                    <p style="margin: 0">Thank you for registering and creating an account. </p>
                    <p style="margin: 0">Your account type is: {{ $user->user_type }}</p>
                    <p style="margin: 0">Your email is verified; Please login into your account at SHEconomy for completing the following 6 steps to make your website live and fully functional. There are some mandatory steps that you must complete in order to make your website live and visible to public and buyers across the world. Some steps are optional which you can skip temporarily, but to benefit from full functionality of SHEconomy, you are advised to complete later. Once you complete all mandatory steps listed below, your website on SHEconomy, as well as your products/services will become live on the SHEconomy website.</p>
                </td>
                </tr>
                <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                    <tr>
                        <td width="260" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                            <tr>
                            <td>
                                <h3>Step1 (Mandatory): Shop Setting</h3>
                            </td>
                            </tr>
                            <tr>
                            <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 25px 0 0 0;">
                                <p style="margin: 0;">Follow link here to view video on how to complete your shop settings. For example, it includes information like your shop name, company name, logo, address, meta tittle, meta description, your website / webstore policies (such as payment policy, shipping policy, refund policy etc.)<a href="">(Link)</a></p>
                                <!-- <a class="button"  type="button">Link</a> -->
                            </td>
                            </tr>
                        </table>
                        </td>
                        <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                        <td width="260" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                            <tr>
                            <td>
                                <h3>Step2 (Mandatory): KYC/ Document verification</h3>
                            </td>
                            </tr>
                            <tr>
                            <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 25px 0 0 0;">
                                <p style="margin: 0;">Follow link here to view video on how to complete Know Your Customer (KYC) requirements and document verification step. It includes information about yourself, your business and establishing that the business is women owned or women led.<a href="">(Link)</a></p>
                                <!-- <a class="button"  type="button">Link</a> -->
                            </td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                    </table>

                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                        <tr>
                        <td width="260" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td>
                                <h3>Step3 (Optional): Payment Setting</h3>
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 25px 0 0 0;">
                                <p style="margin: 0;">Follow link here to view video on how to complete Payment settings on SHEconomy platform. You have the option to link your own payment gateway / payment method (for example: Paypal) to receive funds from sales directly in your account. SHEconomy does not collect any payments on your behalf. This step is optional and you can choose to skip this step and still make your website live by completing all other mandatory steps listed here.<a href="">(Link)</a></p>
                                <!-- <a class="button"  type="button">Link</a> -->
                                </td>
                            </tr>
                            </table>
                        </td>
                        <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                        <td width="260" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td>
                                <h3>Step4 (Optional): Shipping Setting</h3>
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 25px 0 0 0;">
                                <p style="margin: 0;">Follow link here to view video on how to complete Shipping settings. You have the option of defining a uniform shipping rate that gets applied to all your product listings. If shipping varies greatly, across your listings, you can choose to skip this step, or enter a basic shipping amount under this setting and include any remaining (uncovered) shipping in the price of product listing. This step is optional and you can choose to skip this step and still make your website live by completing all other mandatory steps listed here.<a href="">(Link)</a></p>
                                <!-- <a class="button"  type="button">Link</a> -->
                                </td>
                            </tr>
                            </table>
                        </td>
                        </tr>
                    </table>

                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                        <tr>
                        <td width="260" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td>
                                <h3>Step5 (Mandatory): Domain Setting</h3>
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 25px 0 0 0;">
                                <p style="margin: 0;">Follow link here to view video on how to complete Domain settings. Under this feature, you can select your own website address under SHEconomy platform that is closest to your company name (for example: yourcompanyname.sheconomy.in). The domain name you choose now gets locked to your website and cannot be changed later. You can use this website domain address to promote your website on SHEconomy with your buyers, customers, on social media platforms, your business cards etc.<a href="">(Link)</a></p>
                                <!-- <a class="button"  type="button">Link</a> -->
                                </td>
                            </tr>
                            </table>
                        </td>
                        <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                        <td width="260" valign="top">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td>
                                <h3>Step6  (Mandatory): List your goods/Services</h3>
                                </td>
                            </tr>
                            <tr>
                                <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 25px 0 0 0;">
                                <p style="margin: 0;">Follow link here to view video on how to upload listings for your goods or services on SHEconomy, including how to preview your listings before posting and how to edit / delete them once you’ve published (made live) your listings.<a href="">(Link)</a> </p>
                                <!-- <a class="button"  type="button">Link</a> -->
                                </td>
                            </tr>
                            </table>
                        </td>
                        </tr>
                    </table>

                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                        <tr>
                          <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 20px 0 30px 0;">
                            <p style="margin: 0;">If you have any query or need any type of assistance please login in your account and generate a support ticket. You will get a reply within 24 hours but some issues may take longer.
<br><br>
                              Regards,<br>
                              SHEconomy </p>
                        </td>
                        </tr>
                    </table>
                </td>
                </tr>
            </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#ee4c50" style="padding: 30px 30px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                <tr>
                <td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;">
                    <p style="margin: 0;">&reg; SHEconomy<br/>
                <!-- <a href="#" style="color: #ffffff;">Unsubscribe</a> to this newsletter instantly</p> -->
                </td>
                <td align="right">
                    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                    <tr>
                        <td>
                        <a href="https://twitter.com/sheconomyglobal">
                            <i style="color: #fff;" class="fa fa-twitter"></i>
                            <!-- <img src="https://assets.codepen.io/210284/tw.gif" alt="Twitter." width="38" height="38" style="display: block;" border="0" /> -->
                        </a>
                        </td>
                        <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                        <td>
                        <a href="https://www.facebook.com/sheconomyglobal">
                            <i style="color: #fff;"class="fa fa-facebook"></i>
                            <!-- <img src="https://assets.codepen.io/210284/fb.gif" alt="Facebook." width="38" height="38" style="display: block;" border="0" /> -->
                        </a>
                        </td>
                        <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                        <td>
                        <a href="https://www.linkedin.com/company/she-conomy/">
                            <i style="color: #fff;"class="fa fa-linkedin"></i>
                            <!-- <img src="https://assets.codepen.io/210284/fb.gif" alt="Facebook." width="38" height="38" style="display: block;" border="0" /> -->
                        </a>
                        </td>
                        <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                        <td>
                        <a href="https://www.instagram.com/sheconomyglobal/">
                            <i style="color: #fff;"class="fa fa-instagram"></i>
                            <!-- <img src="https://assets.codepen.io/210284/fb.gif" alt="Facebook." width="38" height="38" style="display: block;" border="0" /> -->
                        </a>
                        </td>
                    </tr>
                    </table>
                </td>
                </tr>
            </table>
            </td>
        </tr>
        </table>

      </td>
    </tr>
  </table>
</body>
</html>