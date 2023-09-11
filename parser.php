<body style = "line-height: 1.5rem;font-family:monospace; font-size:1rem;">
<?php
    $FEED = "https://careers.brandtholdings.com/feed/313300";
    $xml = simplexml_load_file($FEED);
    $description = $xml -> jobs -> job[0] -> description;
    //replace all double quotes with single quotes- will not output HTML otherwise
    $description = str_replace('"', '\'', $description);
    $description = str_replace('“', '\'', $description);
    $description = str_replace('”', '\'', $description);
    $dom = new DOMDocument('1.0', 'utf-8');
    $dom -> loadHTML($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $description = str_replace('"', '\'', $dom -> saveHTML());
    echo htmlspecialchars_decode($description);


?>
</body>