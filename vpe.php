
<head>
    <style>
        body{
            font-family: 'Open Sans', sans-serif;
        }
        #filter-locations{
            cursor:pointer;
            font-size:50%;
            margin-left:.25rem;
            margin-top:-.25rem;
            display:inline-block;
        }
    </style>
    <link rel="stylesheet" href="./style.css" type="text/css" />
    <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>
    <script>
        //on document load, add event listener to copy parent style tags and linked stylesheets (inherit styles from parent, as an iframe)
        //use vanillaJS, not Jquery
        document.addEventListener("DOMContentLoaded", () => {
            if (window.parent && window.parent !== window){
            //get iframe parent document using Vanilla JS
            var parent = window.parent.document;
            //get head element of iframe parent document
            var oHead = parent.getElementsByTagName("head")[0];
            //get all style tags from iframe parent document
            var arrStyleSheets = parent.getElementsByTagName("style");
            //get all linked stylesheets from iframe parent document
            var linkedStyleSheets = parent.getElementsByTagName("link");
            //append all style tags from parent to iframe child
            for (var i = 0; i < arrStyleSheets.length; i++) oHead.appendChild(arrStyleSheets[i].cloneNode(true));
            //append all linked stylesheets from parent to iframe child
            for (var i = 0; i < linkedStyleSheets.length; i++) oHead.appendChild(linkedStyleSheets[i].cloneNode(true));}

        })
    </script>
    <script>
        //use jquery to add click event listener to filter locations button
        $(document).ready(function(){
            $("#filter-locations").click(function(){
                //if dropdown already exists, remove it
                if($("#location-dropdown").length){
                    $("#location-dropdown").remove();
                    //change text of button
                    if($(this).text() == "▼"){
                        $(this).text("▲");
                    }
                    else{
                        $(this).text("▼");
                    }
                    return;
                }
                //create a dropdown below #filter-locations with checkboxes for each location
                $(this).after("<div id = 'location-dropdown'></div>");
                $("#location-dropdown").css("position","absolute");
                //set position of dropdown to be vertically centered below #filter-locations
                $("#location-dropdown").css("top",$(this).offset().top + $(this).height() + 10);
                $("#location-dropdown").css("left",$(this).offset().left);
                $("#location-dropdown").css("background-color","white");
                $("#location-dropdown").css("border","1px solid #61a744");

                //add checkboxes for each location 
                //get all locations from table
                var locations = [];
                $(".brandt-holdings-jobs tr td:nth-child(2)").each(function(){
                    locations.push($(this).text());
                })
                //remove duplicates
                locations = [...new Set(locations)];
                //sort alphabetically
                locations.sort();
                //split up locations with semicolons
                for(var i = 0; i < locations.length; i++){
                    if(locations[i].indexOf(";") != -1){
                        var split = locations[i].split(";");
                        locations.splice(i,1);
                        for(var j = 0; j < split.length; j++){
                            locations.push(split[j]);
                        }
                    }
                }
                //trim whitespace from each location
                for(var i = 0; i < locations.length; i++){
                    locations[i] = locations[i].trim();
                }
                //remove ' US,' from each location
                for(var i = 0; i < locations.length; i++){
                    if(locations[i].indexOf(" US,") != -1){
                        locations[i] = locations[i].replace(" US,","");
                    }
                }
                //remove duplicates
                locations = [...new Set(locations)];
                //add checkboxes for each location
                for(var i = 0; i < locations.length; i++){
                    $("#location-dropdown").append("<input type = 'checkbox' id = 'location-checkbox-" + i + "' class = 'location-checkbox' checked>" + locations[i] + "<br>");
                }

                //add event listener to each checkbox
                $(".location-checkbox").click(function(){
                    //get all checked checkboxes
                    var checked = [];

                    $(".location-checkbox:checked").each(function(){
                        //get the text of the checkbox
                        $ch_text = $(this)[0].nextSibling.data
                        //add the text to the checked array
                        checked.push($ch_text);
                        
                    })
                    console.log(checked)
                    //hide all rows
                    $(".brandt-holdings-jobs tr").hide();
                    //show the th row
                    $(".brandt-holdings-jobs tr:nth-child(1)").show();
                    //get all rows
                    var rows = $(".brandt-holdings-jobs tr");
                    //get all location cells
                    var locationCells = $(".brandt-holdings-jobs tr td:nth-child(2)");
                    //loop through each locationCell
                    for(var i = 0; i < locationCells.length; i++){
                        $locationCells_multi = $(locationCells[i]).text().split(";");
                        

                        for(var j = 0; j < $locationCells_multi.length; j++){
                            if(checked.includes($locationCells_multi[j])){
                                $(rows[i+1]).show();
                            }
                        }

                    if(checked.includes($(locationCells[i]).text())){
                        $(rows[i+1]).show();
                    }
                        
                    }

                
                })


                //change text of button
                if($(this).text() == "▼"){
                    $(this).text("▲");
                }
                else{
                    $(this).text("▼");
                    //hide dropdown
                    $("#location-dropdown").hide();
                }
            })
        })
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
</head>
<body>
<?php
     $FEED = "https://careers.brandtholdings.com/feed/398000";
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

    function parseDate($job)
    {
        $date = $job -> date;
        $date_format = "Tue, 29 08 2023 00:00:00 GMT";
        $new_format = "m/d/Y";
        //split $date_format by space
        $date = explode(" ", $date);
        //remove day of week
        array_shift($date);
        //remove last element (timezone)
        array_pop($date);
        $month = $date[1];
        $day = $date[0];
        $year = $date[2];
        //join month, day, and year with slashes
        $date = $month . "/" . $day . "/" . $year;
        return $date;
    }

    function parseLocation($job){
        $location = $job -> multilocation;
        $location = explode(",", $location);
        //remove trailing and leading whitespace from each
        foreach($location as $key => $value){
            $location[$key] = trim($value);
        }
        //find and remove ALL instances of "US"
        $key = array_search("US", $location);
        while($key !== false){
            unset($location[$key]);
            $key = array_search("US", $location);
        }

        $location = implode(", ", $location);
        return $location;


    }


    function jobsToTable($jobs){
        $table = "<table class = 'brandt-holdings-jobs'>";
        $table .= "<tr style = 'font-size:140%'>";
        $table .= "<th>Job Title</th>";
        $table .= "<th>Location(s)<span id = 'filter-locations'>▼</span></th>";
        $table .= "<th>Shift-Type</th>";
        
        $table .= "<th></th>";
        $table .= "</tr>";

        foreach($jobs as $job){
            $table .= "<tr>";
            $table .= "<td><details style = 'display:inline;max-width:max(30vw,600px)'>
            <summary><span style = 'color:#61a744;font-size:120%'><strong>" . $job -> title . "</strong></span></summary>
            <p>" . parseDescription($job) . "</p>
            </details></td>";
            $table .= "<td>" . parseLocation($job) . "</td>";
            $table .= "<td>" . $job -> shifttype . "</td>";
            
            $table .= "<td><a style ='color:#61a744;font-size:120%;font-weight:800' class = 'btn button btn-primary' target='_blank' href =" . $job -> url . ">Apply Now</a></td>";
            $table .= "</tr>";
        }
        $table .= "</table>";
        return $table;
    }

    echo jobsToTable($xml -> jobs -> job);

?>
</body>