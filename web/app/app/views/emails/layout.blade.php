<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <style>
            * {
                margin: 0;
                padding: 0;
            }
            * {
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            }
            img {
                max-width: 100%;
            }
            body {
                -webkit-font-smoothing: antialiased;
                -webkit-text-size-adjust: none;
                width: 100% !important;
                height: 100%;
            }
            .btn {
                text-decoration: none;
                color: #FFF;
                background-color: #666;
                padding: 10px 16px;
                font-weight: bold;
                margin-right: 10px;
                text-align: center;
                cursor: pointer;
                display: inline-block;
            }
            table.head-wrap {
                width: 100%;
            }
            .header.container table td.logo {
                padding: 15px;
            }
            .header.container table td.label {
                padding: 15px;
                padding-left: 0px;
            }
            table.body-wrap {
                width: 100%;
            }
            p, ul {
                margin-bottom: 10px;
                font-weight: normal;
                font-size: 14px;
                line-height: 1.6;
            }
            ul {
                margin-left: 20px;
            }
            p.lead {
                font-size: 17px;
            }
            .container {
                display: block !important;
                max-width: 600px !important;
                margin: 0 auto !important;
                clear: both !important;
            }
            .content {
                padding: 15px;
                max-width: 600px;
                margin: 0 auto;
                display: block;
            }
            .content table {
                width: 100%;
            }
            dt {
                margin-bottom: 12px;
                font-weight: bold;
            }
            dd {
                margin-bottom: 18px;
            }
            a {
                color: white;
                text-decoration: none;
                padding: 10px 0px;
                display: inline-block;
                background-color: #eb6a5a;
                min-width: 110px;
                text-align: center;
            }
            a.primary {
                background-color: #4a89dc;
            }
        </style>
    </head>
    <body bgcolor="#FFFFFF">
        <table class="head-wrap" bgcolor="#eb6a5a">
            <tr>
                <td></td>
                <td class="header container">
                    <div class="content">
                        <table>
                            <tr>
                                <td><img src="{{asset('/assets/images/custom/mainLogo.png')}}"/></td>
                                <td align="right"></td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
        <table class="body-wrap">
            <tr>
                <td></td>
                <td class="container">
                    <div class="content">
                        <table>
                            <tr>
                                <td>
                                    <p>@yield('content')</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td></td>
            </tr>
        </table>
    </body>
</html>
