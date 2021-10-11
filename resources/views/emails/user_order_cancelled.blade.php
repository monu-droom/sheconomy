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
                    <h1 style="font-size: 24px; margin: 0;"> Your order has been cancelled.</h1>
                </td>
                </tr>
                <tr>
                <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 20px 0 30px 0;">
                    <p style="display: flex; align-items: flex-end; float: right;">Order Confirmation <br>Order #@php echo $array['code'] @endphp</p>
                </td>
                </tr>
                <tr>
                  <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 20px 0 30px 0;">
                     <p style="margin: 0;">Hello @php echo $array['user'] @endphp,</p>
                     <p style="margin: 0;">Greetings from SHEconomy </p>
                     <p style="margin: 0;">This email is to let you know that your order has been successfully cancelled. If you need any query related to cancellation please communicate with the seller directly.</p>
                  </td>
                  </tr>
                  <tr>
                    <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 20px 0 30px 0;">
                     <p style="margin: 0">Order Details ,</p>
                     <p style="margin: 0">Order #@php echo $array['code'] @endphp</p>
                     <p style="margin: 0">Placed on @php echo $array['date'] @endphp</p>
                     <p style="margin: 0">Product Name : @php echo $array['product_name'] @endphp </p>
                     <p style="margin: 0">Sold by @php echo $array['seller_name'] @endphp</p>
                     <p style="margin: 0">Reason for Cancellation: Customer Cancelled</p>
                    </td>
                  </tr>
                  <tr>
                    <td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 24px; padding: 20px 0 30px 0;">
                     <p style="margin: 0">Regards,</p>
                     <p style="margin: 0">SHEconomy</p>
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