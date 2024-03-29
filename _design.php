<?php

/**
 * @file _design.php
 * @brief module that display the design of an experiment.
 * *Lists design for an experiment. This module is very simple and therefore could be dropped in favor of just using the function it calls. 
 * This module is for use in the expt page. It needs information contained in the data-description file.
 * @author Nathalie Castells
 * @date 9/27/2018
 */
Global $design;
global $data;

function listItem($value, $label)
{
    global $variableMapping;
    $pcontent = "";

    if ($value == 'NA') {
        $pcontent .= '';
    } elseif (is_null($value)) {
        $pcontent .= '';
    } elseif ($value == 0) {
        $pcontent .= '';
    } elseif ($value == '') {
        $pcontent .= '';
    } elseif ($value == null) {
        $pcontent .= '';
    } else {
        $pcontent = "\n<li class=\"list-group-item \"><b>" . $variableMapping[$label] . ":</b> " . $value;
        $pcontent .= "\n</li>";
    }
    return $pcontent;
}

function getContent($period)
{
    $arrCrop = array();
    global $variableMapping;
    global $design;
    global $data;
    $content = ""; // reset the content
    if ($period['administrative']['description']) {
        $content .= "\n<h3>Description</h3>";

        $content .= "\n\t        <ul class=\"list-group mx-3 mb-3\">";
        if ($period['administrative']['description']) {
            $content .= "\n<li class=\"list-group-item \" style=\"white-space: pre-wrap;\">" . $period['administrative']['description'] . "</li>";
        }
        $content .= " \n      </ul>";
    }
    if (is_array($period['design'])) {
        $hasData = False;
        $data = "\n<h3>Design</h3>";

        $data .= "\n\t <ul class=\"list-group mx-3 mb-3\">";

        // $part = listItem($value, $label);
        if ($period['design']['dateStart']) {
            $hasData = True;
            $data .= "\n<li class=\"list-group-item \"><b>Period:</b> " . $period['design']['dateStart'];
            if ($period['design']['dateEnd']) {
                $data .= " -  " . $period['design']['dateEnd'] . "</li>";
            } else
                $data .= " -  Now </li>";
        }
        if ($period['design']['designTypeLabel']) {
            $hasData = True;
            $data .= "\n<li class=\"list-group-item \"><b>Experiment Design Type:</b> " . $period['design']['designTypeLabel'] . "</li>";
        }
        if ($period['design']['factorCombinationNumber'] != 'NA') {
            $hasData = True;
            $data .= "\n<li class=\"list-group-item \"><b>Number of Combinations:</b> " . $period['design']['factorCombinationNumber'] . "</li>";
        }

        if ($period['design']['numberOfBlocks'] > 0) {
            $hasData = True;
            $data .= "\n<li class=\"list-group-item \"><b>" . $variableMapping['numberOfBlocks'] . ":</b> " . $period['design']['numberOfBlocks'] . "</li>";
        }

        if ($period['design']['numberOfPlots']) {
            $hasData = True;
            $data .= "\n<li class=\"list-group-item \"><b>" . $variableMapping['numberOfPlots'] . ":</b> " . $period['design']['numberOfPlots'] . "</li>";
        }
        if ($period['design']['numberOfReplicates']) {
            $hasData = True;
            $data .= "\n<li class=\"list-group-item \"><b>" . $variableMapping['numberOfReplicates'] . ":</b> " . $period['design']['numberOfReplicates'] . "</li>";
        }
        if ($period['design']['numberOfSubplots']) {
            $hasData = True;
            $data .= "\n<li class=\"list-group-item \"><b>" . $variableMapping['numberOfSubplots'] . ":</b> " . $period['design']['numberOfBlocks'] . "</li>";
        }
        if ($period['design']['numberOfHarvests']) {
            $hasData = True;
            $data .= "\n<li class=\"list-group-item \"><b>" . $variableMapping['numberOfHarvests'] . ":</b> " . $period['design']['numberOfHarvests'] . "</li>";
        }

        $data .= "\n</ul>";
        if ($hasData) {
            $content .= $data;
        }
    }
    if (is_array($period['crops']) and count($period['crops']) > 0) {

        $content .= "\n<h3>Crops</h3>";

        $content .= "\n<div class=\"table-responsive-sm bg-white mx-3 rounded p-3 mb-3\">";
        $content .= "\n\n<table class = \"table  table-responsive-sxm table-sm  table-bordered table-hover  table-condensed\">";
        $content .= "\n<thead class=\"thead-light\">\n\t<tr>\n\t\t<th scope=\"col\">Crop</th>\n\t\t<th scope=\"col\">Years Grown</th>\n\t</tr>\n</thead>\n<tbody>";
        foreach ($period['crops'] as $crop) {
            $id = $crop['id'];
            $arrCrop[$id] = $crop['name'];
            $content .= "\n\t<tr><td class=\"pr-4 \">" . title_case($crop['name']) . "</td><td class=\"pr-4 \">";
            if ($crop['dateStart']) {

                $content .= $crop['dateStart'] . " -  " . $crop['dateEnd'] . "</td>";
            } else {
                $content .= "   ";
            }
            $content .= " </td></tr>";
        }
        $content .= "\n</tbody></table></div>";
    }

    if (is_array($period['cropRotations'])) {
        $info = "";
        $info .= "\n<h3>Crop Rotations</h3>";
        $info .= "\n<div class=\"table-responsive-sm bg-white mx-3 rounded p-3 mb-3\">";
        $info .= "\n\n<table class = \"table  table-responsive-sxm table-sm  table-bordered table-hover  table-condensed\">";
        $info .= "\n<thead class=\"thead-light\">\n\t<tr>\n\t\t<th scope=\"col\">Rotation</th>\n\t\t<th scope=\"col\">Crops</th>\n\t</tr>\n</thead>\n<tbody>";

        foreach ($period['cropRotations'] as $rotation) {

            $info .= "\n    \t<tr>";
            $info .= "\n    \t     \t <td class=\"pr-4 \">" . $rotation['name'] . " <i>(" . $rotation['dateStart'] . " - " . $rotation['dateEnd'] . ")</i> " . "</td><td class=\"pr-4 \">";
            $comma = " ";
            foreach ($rotation['rotationPhases'] as $crop) {
                if ($crop['crop']) {
                $info .= $comma . title_case($arrCrop[$crop['crop']]);
                $comma = " >  ";
                }
            }
            $info .= " </td>";
            $info .= "\n    \t</tr>";
        }

        $info .= "\n</tbody>";
        $info .= "\n</table></div>";
        $content .= $info;
    }
    if (is_array($period['factor'])) {
        $arrFactors = array();
        $arrLevels = array();
        $info = "";
        $info .= "\n<h3>Factors</h3>";
        $info .= "\n<div class=\" bg-white mx-3 rounded   mb-3\">";

        $info .= "<p class=\"mx-1\">Factors are the interventions or treatments which vary across the experiment.</p>";
        foreach ($period['factor'] as $factor) {
            $arrFactors[$factor['id']] = $factor['name'];
            $info .= "\n<h4 class=\"mx-1\">" . title_case($factor['name']) . "</h4>";
            if ($factor['description']) {
                $info .= "<p class=\"ml-3\"><b>Description:</b> " . $factor['description'] . "</p>";
            }
            if ($factor['plotApplication']) {
                $info .= "<p class=\"ml-3\"><b>Application:</b> " . title_case($factor['plotApplication']) . "</p>";
            }

            if (is_array($factor['level'])) {
                $info .= "\n<h5 class=\"mx-3\">Levels</h5>";

                $info .= "\n<div class=\"table-responsive-sm bg-white  rounded p-3 mb-3\"><table class = \"table  table-responsive-sm table-sm table-bordered table-hover  table-condensed\"><thead  class=\"thead-light\"><tr>";
                $info .= "\n<th scope=\"col\">Level Name</th>";
                $info .= "\n<th scope=\"col\">Amount</th>";

                $info .= "\n<th scope=\"col\">Years</th>";
                $info .= "\n<th scope=\"col\">Frequency</th>";
                $info .= "\n<th scope=\"col\">Crop</th>";
                $info .= "\n<th scope=\"col\">Method</th>";
                $info .= "\n<th scope=\"col\">Chemical Form</th>";
                $info .= "\n<th scope=\"col\">Notes</th>";
                $info .= "\n</tr></thead>\n<tbody>";

                foreach ($factor['level'] as $level) {
                    $arrLevels[$level['id']] = $level['name'];
                    $info .= "\n    \t<tr>";

                    $info .= "\n    \t<td class=\"pr-4 \"><b>" . title_case($level['name']) . " </b></td>";
                    $info .= "\n    \t      \t      \t <td class=\"pr-4 \">" . $level['amount'] . " " . $level['unitCode'] . "</td>";
                    $info .= "\n    \t      \t      \t <td class=\"pr-4 \">" . $level['dateStart'] . " - " . $level['dateEnd'] . "</td>";

                    $info .= "\n    \t      \t      \t <td class=\"pr-4 \">" . $level['frequency'] . "</td>";
                    $appliedToCrop = "";
                    if ($level['appliedToCrop']) {
                    $appliedToCrop = $arrCrop[$level['appliedToCrop']];
                }
                    $info .= "\n    \t      \t      \t <td class=\"pr-4 \">" . $appliedToCrop . "</td>";
                  

                    $info .= "\n    \t      \t      \t<td class=\"pr-4 \">" . $level['method'] . "</td>";
                    

                    $info .= "\n    \t      \t      \t <td class=\"pr-4 \">" . $level['chemicalForm'] . "</td>";
                    

                    $info .= "\n    \t      \t      \t <td class=\"pr-4 \"><i> " . $level['notes'] . "</i></td>";

                    $info .= "\n    \t      \t </tr>";
                }
                $info .= "\n    \t</tbody></table></div>";
            }
        }

        $info .= "\n    \t</div>";
        $content .= $info;
    }

    if (is_array($period['factorCombinations'])) {
        $info = "";
        $info .= "\n<h3>Factor Combinations</h3>";
        $info .= "\n<div class=\" bg-white mx-3 rounded mb-3\">";
        $info .= "<p  class=\" bg-white mx-3\">Factor Combinations are the combination of factors applied to different plots on the experiment.</p>";

        $info .= "\n<div class=\"table-responsive-sm bg-white rounded p-3 mb-3\"><table class = \"table  table-responsive-sm table-sm table-bordered table-hover  table-condensed\"><thead  class=\"thead-light\"><tr>";
        $info .= "\n<th scope=\"col\">Factor Combination</th>";
        $info .= "\n<th scope=\"col\">Time Coverage</th>";
        $info .= "\n<th scope=\"col\">Notes</th>";
        $info .= "\n</tr></thead>\n<tbody>";

        foreach ($period['factorCombinations'] as $fr) {
            $info .= "\n<tr>";
            $info .= "\n<td class=\"pr-4 \"><b>" . $fr['name'] . "</b></td>";
            $info .= "\n<td class=\"pr-4 \">" . $fr['dateStart'] . " - " . $fr['dateEnd'];

            $info .= "</td>";
            $info .= "\n<td class=\"pr-4 \"><i>" . $fr['description'] . "</i></td>";
            $info .= "</tr>";
        }
        $info .= "\n    \t</table></div>";
        $info .= "\n    \t</div>";
        $content .= $info;
    }
    if (is_array($period['measurements']) and count($period['measurements']) > 0) {

        $content .= "\n<h3>Measurements</h3>";
        $content .= "\n<div class=\" bg-white mx-3 rounded  p-3 mb-3\">";
        $content .= "\n<div class=\"table-responsive-sm bg-white\">";
        $content .= "\n \t<table class = \"table  table-responsive-sm table-sm table-bordered table-hover table-condensed\">";
        $content .= "\n  \t     \t   <thead  class=\"thead-light\">";
        $content .= "\n  \t     \t   \t   <tr>";
        $content .= "\n  \t     \t   \t   \t   <th scope=\"col\">Variable</th>";
        $content .= "\n  \t     \t   \t   \t   <th scope=\"col\">Unit</th>";
        $content .= "\n  \t     \t   \t   \t   <th scope=\"col\">Collection <br />Frequency</th>";

        $content .= "\n  \t     \t   \t   \t   <th scope=\"col\">Material</th>";
        $content .= "\n  \t     \t   \t   \t   <th scope=\"col\"  class=\" w-25\">Description</th>";
        $content .= "\n  \t     \t   \t   \t   <th scope=\"col\">Crop</th>";
        $content .= "\n  \t     \t   \t   </tr>";
        $content .= "\n  \t     \t   </thead>";
        $content .= "\n  \t   \t   <tbody>";
        foreach ($period['measurements'] as $measurement) {
            $vcrop = '';
            if ($measurement['crop']) {
                $vcrop = $arrCrop[$measurement['crop']];
            }
            $content .= "\n<tr>";
            $content .= "\n<td class=\"pr-4 \">" . title_case($measurement['variable']) . "</td>";
            $content .= "\n<td class=\"pr-4 \">" . $measurement['unitCode'] . "</td>";
            $content .= "\n<td class=\"pr-4 \">" . $measurement['collectionFrequency'] . "</td>";
            $content .= "\n<td class=\"pr-4 \">" . $measurement['material'] . "</td>";
            # $content .= "\n<td class=\"pr-4 \">" . $measurement['scale'] . "</td>";
            $content .= "\n<td class=\"pr-4 w-25\">" . $measurement['description'] . "</td>";
            $content .= "\n<td class=\"pr-4 \">" . $vcrop . "</td>";

            $content .= "\n</tr>";
        }

        $content .= "\n</tbody>";
        $content .= "\n</table></div></div>";
    }
    return $content;
}




$strTab = "<div class=\"row\">";
$strTab .= "\n\t  <div class=\"col-12\">";
$strTab .= "\n\t \t   <h2 class=\"mx-3\">Experimental Design</h2>";
$strTab .= "\n\t  </div>";
$strTab .= "\n \t <div class=\"col-12 mx-auto\"> ";


$strTab .= "\n \t\t    <div class=\"mt-3 mx-3\" id=\"accordionDesign\"> \n";
$nbDesign = count($design);
if (count($design) == 1) {
    $strTab .= getContent($design[0]);
}
else {
$i = 0;
$tabID = 0;

foreach ($design as $period) {
    $tabID = $tabID +1;
    $defaultTitle = "Main Period";
    if ($period['administrative']['name'] != null) {
    $Title = $period['administrative']['name'] ;}
    else {$Title = $defaultTitle;}
    $content = getContent($period);
    if ($i != 0) {
        $active = "";
        $collapse = " show ";
        $expanded = "true";
    } else {
        $active = " in";
        $collapse = "  ";
        $expanded = "false";
    }

    //$tabID = str_replace(' ', '', $Title);
    //$tabID = str_replace(':', '', $tabID);
    //$tabID = str_replace('-', '', $tabID);
    if ($period['design']['dateStart']) {
        $Title .= " - " . $period['design']['dateStart'] . " - " . $period['design']['dateEnd'];
    }

    $strTab .= "\n\t<div class=\"card  bg-light\">";
    $strTab .= "\n\t\t<div class=\"card-header \" id=\"heading" . $tabID . "\">";

    $strTab .= "\n\t\t<h2 class=\"mb-0\">";
    $strTab .= "\n\t\t\t<button class=\"btn btn-link\"  data-toggle=\"collapse\" data-target=\"#collapse" . $tabID . "\" aria-expanded=\"" . $expanded . "\" aria-controls=\"" . $tabID . "\">";
    $strTab .= "\n\t\t\t\t	" . $Title;
    $strTab .= "\n\t\t\t						</button>";
    $strTab .= "\n\t\t\t						</h2>";
    $strTab .= "\n\t</div>";
    $strTab .= "\n\t<div id=\"collapse" . $tabID . "\" class=\"collapse " . $collapse . " fade bg-light\" aria-labelledby=\"heading" . $tabID . "\" data-parent=\"#accordionDesign\">";
    $strTab .= "\n\t<div class=\"card-body\">" . $content . "</div>";
    $strTab .= "\n\t</div>";
    $strTab .= "\n\t</div>";
}
}
$strTab .= "\n\t\t</div>";

$strTab .= "\n\t</div>";
$strTab .= "\n</div>";

echo $strTab;
?>