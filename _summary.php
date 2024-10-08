<?php

/**
 * @file _summary.php
 * @brief module that display summary of information contained in the data-description array.
 *
 * @author Nathalie Castells
 * @email nathalie.castells@rothamsted.ac.uk
 *
 */
global $person;

function getPersonInfo($personDetails)
{


    $line = "";
    if (isset($personDetails['name'])) {
        $line .= "\n<li class=\"list-group-item \"><h4 class=\"mt-3\">" . $personDetails['name'] . "</h4></li>";
    } elseif (isset($personDetails['givenName']) and isset($personDetails['familyName'])) {
        $line .= "\n<li class=\"list-group-item \"><h4 class=\"mt-3\">" . $personDetails['givenName'] . "  " . $personDetails['familyName'] . "</h4></li>";
    }
    if (isset($personDetails['jobTitle'])) {
        $line .= "\n<li class=\"list-group-item pl-5\"><b>Role: </b>" . title_case($personDetails['jobTitle']) . "</li>";
    }
    if (isset($personDetails['sameAs'])) {
        $line .= "\n<li class=\"list-group-item pl-5\"><b>ORCID: </b><a target =\"_blank\"  href=\"" . $personDetails['sameAs'] . "\">" . $personDetails['sameAs'] . "</a> <sup><i class=\"fa fa-external-link\" aria-hidden=\"true\"></i></sup>" . "</li>";
    }
    if (!isset($personDetails['affiliation']['type'])) {
        $personDetails['affiliation']['type'] = "NONE";
    }
    if ($personDetails['affiliation']['type'] == "Organization") {
        $line .= "\n<li class=\"list-group-item pl-5\"><b>Organisation: </b>" . $personDetails['affiliation']['name'] . "</li>";
        $line .= "\n<li class=\"list-group-item pl-5\"><b>Address: </b>" . $personDetails['affiliation']['address'] . "</li>";
    }
    $line .= "";
    return $line;
}
?>
<div class="mx-3">
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="list-group">
                    <?php
    $line = '';
    if (isset($experiment['administrative']['localIdentifier'])) {
        $line .= "<li class=\"list-group-item \" ><b>Experiment Code: </b>";
        $line .= $experiment['administrative']['localIdentifier'];
        $line .= "</li>";
    }

        $line .= "<li class=\"list-group-item \" ><b>Experiment Site: </b>";
        $line .= "<a href=\"site/".strtolower($stationName)."\">".$stationName."</a>";
        $line .= "</li>";
   
    if (isset($experiment['administrative']['disambiguatingDescription'])) {
        $line .= "<li class=\"list-group-item\"  style=\"white-space: pre-wrap;\" ><b>Objectives: </b>";
        $line .= $experiment['administrative']['disambiguatingDescription'];
        $line .= "</li>";
    }
    if (isset($experiment['administrative']['description'])) {
        $line .= "<li class=\"list-group-item\"  style=\"white-space: pre-wrap;\" ><b>Description: </b>";
        $line .= $experiment['administrative']['description'];
        $line .= "</li>";
    }

    echo $line;
    ?>
                    <li class="list-group-item "><b>Date Start: </b><?php echo $experiment['dateStart']; ?>
                    </li>
                    <?php if ($experiment['dateEPEnd']) {?>
                    <li class="list-group-item "><b>Establisment Period End: </b><?php echo $experiment['dateEPEnd']; ?>
                    </li>
                    <?php } ?>
                    <li class="list-group-item "><b>Date End: </b>
                        <?php if (!$experiment['dateEnd']) {echo"Ongoing";} else { echo $experiment['dateEnd'];}; ?>
                    </li>


                </ul>
            </div>
            <div class="col">
                <?php
            $line = '';$coords = ''; $siteName = '';
            if (is_array($site['location']['geoLocationPoint'])) {
                $line .= "\n<div id=\"mapid\" style=\"width: 500px; height: 400px;\"></div>";
                $coords ="[". $site['location']['geoLocationPoint']['pointLongitude'] . " , " . $site['location']['geoLocationPoint']['pointLatitude']."]";
                $siteName = $site['administrative']['name'];
            }
            echo $line;
            ?>
                <script>
                    mapboxgl.accessToken =
                        'pk.eyJ1IjoiZXJhZGV2bmF0aCIsImEiOiJja2twZHN4MWYwYnVoMm9zMTZjeW9mMDF2In0.f-XLtnBSQXAFI1XBruy3uQ';
                    var map = new mapboxgl.Map({
                        container: 'mapid', // container id
                        //style: 'mapbox://styles/mapbox/streets-v11', // style URL
                        style: 'mapbox://styles/mapbox/satellite-v9', // style statellite
                        center: <?php echo $coords; ?> , // starting position [lng, lat]
                        zoom : 15 // starting zoom
                    });

                    var marker = new mapboxgl.Marker()
                        .setLngLat( <?php echo $coords; ?> )
                        .setPopup(new mapboxgl.Popup().setHTML(
                            "<b><?php echo $siteName ?></b>")) // add popup
                        .addTo(map);
                    marker.togglePopup();
                </script>
                <div id="mapid"></div>
            </div>
        </div>
    </div>
    <?php
        // if ( $hasTimeline ){
        // echo(" <h3 class=\"my-3 mt-5\">Timeline</h3>");
        // include '_timeline.php';
        // } else {; }
if (!isset($person['contributors'])) {}
else {
if (count($person['contributors']) > 0) {
    $line = "<h3 class=\"my-3 mt-5\">Key Contacts</h3> <ul class=\"list-group  mx-3\">";
    echo $line;
    foreach ($person['contributors'] as $personDetails) {
        $list = getPersonInfo($personDetails);
        echo $list;
    }
    echo "</ul>";
}
}
?>
    <h3 class="my-3 mt-5">Funding</h3>
    <ul class="list-group mx-3">
        <li class="list-group-item ">
            The e-RA
            database, including the published datasets generated from it,
            is part of the <a href="https://www.rothamsted.ac.uk/national-capability/long-term-experiments-0">Rothamsted Long-Term Experiments - National Bioscience Research Infrastructure (RLTE-NBRI)
            </a>, which also includes the Long-Term
                Experiments, the Sample
                Archive and Rothamsted's environmental monitoring activities including the weather stations and its
            role in the UK Environmental
                Change Network.
        </li>
        <li class="list-group-item ">The RLTE-NBRI is supported by the Lawes Agricultural Trust and the Biotechnology
            and
            Biological Sciences Research Council
            (Grants BBS/E/C/00005189 (2012-2017); BBS/E/C/000J0300 (2017-2022); BBS/E/RH/23NB0007 (2023-2028)).
        </li>
    </ul>
</div>