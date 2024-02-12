<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

    <title><?= $title ?></title>
    <style>
        @page {
            margin: 0.3cm 1cm 0.3cm 1cm;
        }

        body {
            font-family: sans-serif;
            margin: 1.5cm 0;
            font-size: 0.8em;
        }

        #header,
        #footer {
            position: fixed;
            left: 0;
            right: 0;
            color: #aaa;
            font-size: 0.7em;
        }

        #header {
            top: 0;
            border-bottom: 0.1pt solid #aaa;
        }

        #footer {
            bottom: 0;
            border-top: 0.1pt solid #aaa;
        }

        span.page-number {
            float: right;
        }

        span.page-number:before {
            content: "Page " counter(page);
        }

        span.author {
            float: right;
            font-style: italic;
        }

        table {
            width: 100%;
            page-break-before:auto;
        }

        thead {
            background-color: #fff;
            font-size: 0.8em;
        }

        tbody {
            background-color: #fff;
        }

        th,
        td {
            padding: 3pt;
            border: 1pt solid #aaa;
        }

        table.collapse {
            border-collapse: collapse;
            border: 1pt solid #aaa;
        }

        table.collapse td {
            border: 1pt solid #aaa;
        }

        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div id="header">
        <p><?= $title ?> <span class="author">Dicetak oleh : <?= $this->user->profile_username($this->session->userdata('user_name'))->row()->nama ?></span></p>
    </div>
    <div id="content">

    </div>
    <div id="footer">
        <p>Copyright <?= date('Y') ?> ::: SIMEV (<?= getSetting('version_app') ?>) <span class="page-number"></span></p>
    </div>
</body>

</html>