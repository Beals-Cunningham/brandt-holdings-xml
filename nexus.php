<head>
    <link rel="stylesheet" href="./style.css" type="text/css" />
</head>
<body>
<?php
     $FEED = "https://careers.brandtholdings.com/feed/398200";
     $xml = simplexml_load_file($FEED);

    function parseDescription($job)
    {
        $description = $job -> description;
        //replace all double quotes with single quotes- will not output HTML otherwise
        $description = str_replace('"', '\'', $description);
        $description = str_replace('“', '\'', $description);
        $description = str_replace('”', '\'', $description);
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom -> loadHTML($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $description = str_replace('"', '\'', $dom -> saveHTML());
        return htmlspecialchars_decode($description);
    }

    function getURLParams($job)
    {
        $url = $job -> url;
        $url = parse_url($url);
        parse_str($url['query'], $query);
        return $query;
    }

    function jobsToTable($jobs){
        $table = "<table class = 'brandt-holdings-jobs'>";
        $table .= "<tr>";
        $table .= "<th>Job Title</th>";
        $table .= "<th>Location</th>";
        $table .= "<th>Reference Number</th>";
        $table .= "<th>Business Unit</th>";
        $table .= "<th>Facility</th>";
        $table .= "<th>Date</th>";
        $table .= "<th>URL</th>";
        $table .= "</tr>";
        foreach($jobs as $job){
            $table .= "<tr>";
            $table .= "<td><details style = 'display:inline'>
            <summary></summary>
            <p>" . parseDescription($job) . "</p>
            </details>" . $job -> title . "</td>";
            $table .= "<td>" . $job -> location . "</td>";
            $table .= "<td>" . $job -> referencenumber . "</td>";
            $table .= "<td>" . $job -> business_unit . "</td>";
            $table .= "<td>" . $job -> facility . "</td>";
            $table .= "<td>" . $job -> date . "</td>";
            $table .= "<td><a href =" . $job -> url . ">View</a></td>";
            $table .= "</tr>";
        }
        $table .= "</table>";
        return $table;
    }

    echo jobsToTable($xml -> jobs -> job);

?>
</body>