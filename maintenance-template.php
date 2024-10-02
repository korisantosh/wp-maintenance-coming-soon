<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon</title>
    <style>
      html,body {
        height: 100%;
        margin: 0;	padding: 0 0 1px 0;
        background-color:#fff;
        font-family: arial;
      }
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #fff;
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: center;
        }
        img {
            max-width: 100%;
        }
        .logo img {
            max-width: 120px;
            margin-top: 20px;
        }
        h1, h2 {
            margin-top: 20px;
            margin-bottom: 10px;
            color: #333;
        }
        p {
            color: #666;
            line-height: 1.5em;
            opacity: 0.8;
        }
        .maintenance-page {
          max-width: 550px;
        }
        .countdown {
            margin-top: 20px;
            font-size: 1.5em;
            color: #333;
        }
        .hours::before, .minutes::before, .seconds::before {
            content: ":";
            color: #999;
            display: inline-block;
            text-align: center;
            width: 0.5em;
        }
        .days::after {
            content: "d";
            color: #999;
        }
        .hours::after {
            content: "h";
            color: #999;
        }
        .minutes::after {
            content: "m";
            color: #999;
        }
        .seconds::after {
            content: "s";
            color: #999;
        }
        .template-template3 .countdown {
            margin-top: 20px;
            font-size: 2em;
            color: #333;
        }
        .maintenance-page {
            max-width: 100%;
            padding: 2em 1.5em;
          }
        @media (min-width: 1025px) {
          .template-template3 .countdown {
            margin-top: 20px;
            font-size: 3.5em;
            color: #333;
        }
          .template-template3 .logo,
          .template-template2 .logo {
            position: fixed;
            margin-top: 0;
            top: 15px;
            left: 15px;
          }
        }
    </style>
</head>
<body>
    <?php mmcs_maintenance_template_content(); ?>
</body>
</html>
