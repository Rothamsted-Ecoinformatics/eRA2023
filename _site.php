<?php

/**
 * @file _site.php
 * @brief module that display site information contained in the data-description array.
 *
 * @author Nathalie Castells
 * @email nathalie.castells@rothamsted.ac.uk
 *
 * 
 */



/**
 * getFieldInfo : formats the information about the field
 *
 * @param  $site
 * @return string
 */
function getProperties($site)
{
    $content = '';
    if (is_array($site['soilProperty']) and count($site['soilProperty']) > 0) {

        $content .= "\n<h3 class=\"mx-3\">Soil Properties</h3>";
        $content .= "\n<div class=\"table-responsive-sm bg-white m-5 p-3\">";
        $content .= "\n\n<table class = \"table  table-responsive-sm table-sm  table-hover\"><thead  class=\"thead-light\"><tr>";
        $content .= "\n<th scope=\"col\">Variable</th>";

        $content .= "\n<th scope=\"col\">Value</th>";
        // $content .= "\n<th scope=\"col\">Measured at Depth</th>";

        $content .= "\n<th scope=\"col\">Reference Year</th>";
        $content .= "\n<th scope=\"col\">Is Estimated</th>";
        $content .= "\n<th scope=\"col\">Is Baseline</th>";
        $content .= "\n</tr>";
        $content .= "\n</thead>";
        $content .= "\n<tbody>";
        foreach ($site['soilProperty'] as $measurement) {
            if ($measurement['isEstimated'] == TRUE) {
                $isEst = "YES";
            } else {
                $isEst = "NO";
            }
            if ($measurement['isBaseline'] == TRUE) {
                $isBas = "YES";
            } else {
                $isBas = "NO";
            }
            $content .= "\n<tr>";
            $content .= "\n<td>" . htmlentities(ucfirst($measurement['variableMeasured'] )). "</td>";
            $content .= "\n<td>" . $measurement['valueReference'] . $measurement['unitCode'] . " (" . $measurement['unitText'] . ") </td>";
            // $content .= "\n<td> Depth (TODO) </td>";
            $content .= "\n<td>" . $measurement['refYear'] . "</td>";
            $content .= "\n<td>" . $isEst . "</td>";
            $content .= "\n<td>" . $isBas . "</td>";

            $content .= "\n</tr>";
        }

        $content .= "\n</tbody>";
        $content .= "\n</table></div>";
    }
    return $content;
}

if (!isset($stationName))  
 {
    $stationName = 'StationName';
 }
 
?>
<h2 class="mx-3">Site: <?php echo $site['administrative']['name']. " - ". $stationName;?></h2>
<div class="mx-5">
    <?php

    $line = "<ul class=\"list-group m-5\">";
    $line .= "<li class=\"list-group-item \" ><b>Experiment Site: </b>";
    $line .= "<a href=\"site/".strtolower($stationName)."\">".$stationName."</a>";
    $line .= "</li>";
    if (isset($site['administrative']['doi'])) {
        $line .= "\n<li class=\"list-group-item \"><b>DOI:</b> <a href=\"https://doi.org/" . $site['administrative']['doi'] . "\">" . $site['administrative']['doi'] . "</li>";
    }
    if (isset($site['administrative']['description'])) {
        $line .= "\n<li class=\"list-group-item \" style=\"white-space: pre-wrap;\" ><b>Description:</b> " . $site['administrative']['description'] . "</li>";
    }
    if (isset($site['administrative']['management'])) {
        $line .= "\n<li class=\"list-group-item \" style=\"white-space: pre-wrap;\" ><b>Management:</b> " . $site['administrative']['management'] . "</li>";
    }

    if (isset($site['administrative']['visitsAllowed'])) {

        $line .= "\n<li class=\"list-group-item \"><b>Visit Permitted?:</b> ".$site['administrative']['visitsAllowed']." </li>";

        if (isset($site['administrative']['visitingArrangements'])) {
            $line .= "\n<li class=\"list-group-item \" style=\"white-space: pre-wrap;\" ><b>Visiting Arrangments:</b> " . $site['administrative']['visitingArrangements'] . "</li>";
        }
    }
    if (isset($site['location']['elevation'])) {
        $line .= "\n<li class=\"list-group-item \"><b>Elevation:</b> " . $site['location']['elevation'] . " " . $site['location']['elevationUnit'] . "</li>";
    }
    if (isset($site['location']['slope'])) {
        $line .= "\n<li class=\"list-group-item \"><b>Slope:</b> " . $site['location']['slope'] . "</li>";
    }
    if (isset($site['location']['slopeAspect'])) {
        $line .= "\n<li class=\"list-group-item \"><b>Slope:</b> " . $site['location']['slopeAspect'] . "</li>";
    }

    if (isset($site['location']['geoLocationPoint'])) {
        /*https://maps.google.com/?q=<lat>,<lng>*/
        $line .= "\n<li class=\"list-group-item \"><b>Geolocation:</b> <a target=\"out\" href=\"https://maps.google.com/?q=". $site['location']['geoLocationPoint']['pointLatitude'] ."," . $site['location']['geoLocationPoint']['pointLongitude'] . "\"> <i class=\"fas fa-map-marker\"></i> &nbsp;&nbsp; ".  $site['location']['geoLocationPoint']['pointLatitude'] .", " . $site['location']['geoLocationPoint']['pointLongitude'] .  "</a></li>";
    }

    $line .= "\n</ul>";

    if (isset($site['soil'])) {

        $line .= "<h2 class=\"mx-3\">Soil</h2>";

        $line .= "<ul class=\"list-group m-5\">";
        if ($site['soil']['soilTypeLabel']) {
            $line .= "\n<li class=\"list-group-item \"  style=\"white-space: pre-wrap;\" ><b>Type:</b> " . title_case($site['soil']['soilTypeLabel']);

            if ($site['soil']['soilDescription']) {
                $line .= "\n<br />" . $site['soil']['soilDescription'] . "</li>";
            }
            $line .= "\n</li>";
        }
        $line .= "\n</ul>";
    }
    if (isset($site['soilProperty'])) {
    $line .= getProperties($site);
    }
    echo $line;
    ?>
 
</div>
