<!doctype html>
<html>
    <head>
    <style type="text/css">
        body {
            margin: 0 !important;
            padding: 0 !important;
            -webkit-text-size-adjust: 100% !important;
            -ms-text-size-adjust: 100% !important;
            -webkit-font-smoothing: antialiased !important;
        }
        img {
            border: 0 !important;
            outline: none !important;
        }
        p {
            Margin: 0px !important;
            Padding: 0px !important;
        }
        table {
            border-collapse: collapse;
            mso-table-lspace: 0px;
            mso-table-rspace: 0px;
        }
        td, a, span {
            border-collapse: collapse;
            mso-line-height-rule: exactly;
        }
        .ExternalClass * {
            line-height: 100%;
        }
        .em_defaultlink a {
            color: inherit !important;
            text-decoration: none !important;
        }
        span.MsoHyperlink {
            mso-style-priority: 99;
            color: inherit;
        }
        span.MsoHyperlinkFollowed {
            mso-style-priority: 99;
            color: inherit;
        }
        @media only screen and (min-width:481px) and (max-width:699px) {
        .em_main_table {
            width: 100% !important;
        }
        .em_wrapper {
            width: 100% !important;
        }
        .em_hide {
            display: none !important;
        }
        .em_img {
            width: 100% !important;
            height: auto !important;
        }
        .em_h20 {
            height: 20px !important;
        }
        .em_padd {
            padding: 20px 10px !important;
        }
        }
        @media screen and (max-width: 480px) {
            .em_main_table {
                width: 100% !important;
            }
            .em_wrapper {
                width: 100% !important;
            }
            .em_hide {
                display: none !important;
            }
            .em_img {
                width: 100% !important;
                height: auto !important;
            }
            .em_h20 {
                height: 20px !important;
            }
            .em_padd {
                padding: 20px 10px !important;
            }
            .em_text1 {
                font-size: 16px !important;
                line-height: 24px !important;
            }
            u + .em_body .em_full_wrap {
                width: 100% !important;
                width: 100vw !important;
            }
        }
</style>
       
    </head>
    <body>
       <table class="em_full_wrap" valign="top" width="50%" cellspacing="0" cellpadding="0" border="0" align="center" style="min-height:350px; background-color:#9e9e9e17;">
            <tr bgcolor="#14a0fa" height="60px">
                <td colspan=4 align="center" >
                    <span style="padding-left:-10px; font-size:20px; font-weight:bold; color:white">HELP US, HELP YOU</span> 
                </td>
            </tr>
            <tr>
                <td colspan=4>
                        <table class="em_full_wrap" valign="top" width="60%" cellspacing="0" cellpadding="0" border="0" bgcolor="#fffff" align="center" style="min-height:15px">
                            <tr style="height:15px">
                                <td style="background-color:#ffff00e3"></td>
                                <td style="background-color:#92f100fa"></td>
                                <td style="background-color:#03a9f4c2"></td>
                                <td style="background-color:#e91e63b8"></td>
                            </tr>
                        </table>
                        <table class="em_full_wrap" valign="top" width="60%" cellspacing="0" cellpadding="0" border="0"  align="center" style="min-height:200px;background-color:#ffff">
                            <tr>
                                <td align="center" style="padding-right:25px;color:gray;" colspan=4><h3> {{$employee["first_name"]}}, Tell us what you think</h3></td>
                            </tr>
                            <tr> 
                                <td align="center">
                                    <a  href="{{$employee['url']}}"><button style="padding:8px;font-size:12px;font-weight:bold;color:gray;border-radius:3px;border:solid gray 1px;">I'LL TAKE THE SURVEY</button></a>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="padding-right:25px; color:gray" colspan=4><h5> Your opinion really matters to us</h5></td>
                            </tr>
                           
                        </table>

                </td>
            </tr>
       </table>

    </body>
</html>