<body>
<?php
    $FEED = "https://careers.brandtholdings.com/feed/313300";
    $xml = simplexml_load_file($FEED);
    $description = $xml -> jobs -> job[0] -> description;
    //replace all double quotes with single quotes- will not output HTML otherwise
    $description = str_replace('"', '\'', $description); //these str_replaces do not work?!?
    $description = str_replace('“', '\'', $description); //  |
    $description = str_replace('”', '\'', $description); // \/

    $description = str_replace('Service', 'SERVICE', $description);     //test str_replace: this one works ¯\_(ツ)_/¯
    
    echo $description;
?>
</body>