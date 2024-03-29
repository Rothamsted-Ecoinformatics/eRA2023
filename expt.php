<?php
/** 
 * @file expt.php
 * @brief Experiment File. 
 * 
 * @author Nathalie Castells
 * 
 * This page describe the experiment. It is a frame in which one can add modules
 * In this development stage, some variables are encoded here but will eventually come from database or URL.
 * @date 9/27/2018
 * 
 * 
 */
include_once 'includes/init.php'; // these are the settings that refer to more than one page

if (! isset($expt)) {
    $expt = 'rbk1';
}
$exptFirst = $expt[0];
switch ($exptFirst) {
    case "r":
        $stationName = 'Rothamsted';
        break;
    case "s":
        $stationName = 'Saxmundham';
        break;
    case "b":
        $stationName = "Broom's Barn";
        break;
    case "w":
        $stationName = "Woburn";
        break;
}
// default experiment arbitrarily broadbalk. We could be more clever? random experiment?
$exptFolder = 'metadata/' . $expt;
$pageinfo = getPageInfo($expt);
$KeyRef = '';
if (is_array($pageinfo)) {
    $KeyRef = $pageinfo['KeyRef'];
}

$page_title .= $pageinfo['Experiment']; // This is used in the head file as the title tag

$filedatacite = $exptFolder . '/' . 'experiment.json';
$hasDatacite = file_exists($filedatacite);
$datacite = "";
if ($hasDatacite) {
    $datacite = file_get_contents($filedatacite);
    $datacite = utf8_encode($datacite);
    $experiment = json_decode($datacite, true);
    $page_description = htmlentities($experiment['administrative']['description']);
}

$fileExperiments = 'metadata/default/experiments.json';
$hasExperiments = file_exists($fileExperiments);
if ($hasExperiments) {
    $Experiments = file_get_contents($fileExperiments);
    $Experiments = utf8_encode($Experiments);
    $Experiments = json_decode($Experiments, true);
    
}
$infofilessource = 'metadata/default/infofiles.json';
$hasFiles = file_exists($infofilessource);
if ($hasFiles) {
    $infofiles = file_get_contents($infofilessource);
    $infofiles = utf8_encode($infofiles);
    $arrInfofiles = json_decode($infofiles, true);
}
if ($displayValue == 0) {# intranet site, we want to see all the pages including the ones that are not reviewed.
    $pairs = array(
        'exptID' => $expt,
        'isInfo'=> '1',  
        'Type' => 'html'
);
} else { # live site: only display the pages that have been reviewed
    $pairs = array(
        'exptID' => $expt,
        'isInfo'=> '1',  
        'isReviewed'=> '1',
        'Type' => 'html'
    );  
}
$arrFilteredfiles = multiSearch($arrInfofiles, $pairs);
$hasDocs = count($arrFilteredfiles);

$fileTimeline = $exptFolder . '/' . 'timeline.json';
$hasTimeline = file_exists($fileTimeline);
if ($hasTimeline) {
    $timeline = file_get_contents($fileTimeline);
}

$filePerson = $exptFolder . '/person.json';
$hasPerson = file_exists($filePerson);
if ($hasPerson) {
    $jdata = file_get_contents($filePerson);
    $jdata = utf8_encode($jdata);
    $person = json_decode($jdata, true);
}

$fileSite = $exptFolder . '/site.json';
$hasSite = file_exists($fileSite);
if ($hasSite) {
    $jdata = file_get_contents($fileSite);
    $jdata = utf8_encode($jdata);
    $site = json_decode($jdata, true);
}

$fileDesign = $exptFolder . '/design.json';
$hasDesign = file_exists($fileDesign);
if ($hasDesign) {
    $jdata = file_get_contents($fileDesign);
    $jdata = utf8_encode($jdata);
    $design = json_decode($jdata, true);
    $showDesign = FALSE;
    if ($design[0]['administrative']['type'] != null) {
        $showDesign = TRUE;
    }
}

$fileDataset = $exptFolder . '/' . 'datasets.json';

$hasDatasets = file_exists($fileDataset);
$displayDatasets = 0;
if ($hasDatasets) {
    $jdatasets = file_get_contents($fileDataset);
    $jdatasets = utf8_encode($jdatasets);
    $datasets = json_decode($jdatasets, true);
    foreach ($datasets as $dataset) {
        if ($dataset['isReady'] > $displayValue) {
            $displayDatasets = 1;
        }
    }
}


$fileMedia = $exptFolder . '/' . 'medialist.html';
$hasMedia = file_exists($fileMedia);

$imageurl =  $exptFolder . '/' . 'images.json';
$hasImages = file_exists($imageurl);
if ($hasImages) {
    $jimages = file_get_contents($imageurl);
    $jimages = utf8_encode($jimages);
    $images = json_decode($jimages, true);
    $displayImages = 0;
   
    foreach ($images as $image) {
        $displayImages += 1;
    }
   
}

?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>

    <?php

        include 'includes/meta.html'; // that is the <meta and link tags> superseeds head.html

        $script = ''; // $script is added to the header as the
        if ($hasDatacite) {
            $script = "<script type=\"application/ld+json\">" . $datacite . "</script>";
            echo $script;
        }
        ?>

</head>

<body>
    <div class="container bg-white px-0">

        <?php
            include 'includes/header.html'; // all the menus at the top

            // -- start dependant content ---------------------------------------------------------
            ?>
        <div id="idExpt" class="p-0 mb-0">
            <h1 class="mx-3"><?php
// /experimentname is found in the datadescription file.
echo title_case($experiment['administrative']['name']);
?></h1>
            <div class="row">
                <div class="col-12 pt-3">
                    <ul class="nav nav-tabs nav-fill text-body " id="Expttabs" role="tablist" >

                        <li class="nav-item">
                            <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview"  aria-controls="overview" aria-selected="true">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="site-tab" data-toggle="tab" href="#site"  aria-controls="site" aria-selected="false">Site</a>
                        </li>
                        <?php if ($showDesign == TRUE) {?>
                        <li class="nav-item">
                            <a class="nav-link" id="design-tab" data-toggle="tab" href="#design"  aria-controls="design" aria-selected="false">Design</a>
                            </li>
                        <?php }?>

                        <li class="nav-item">
                            <a class="nav-link" id="datasets-tab" data-toggle="tab" href="#datasets"  aria-controls="datasets" aria-selected="false">Datasets</a>
                        </li>
                        <?php if ($displayImages >0) {?>
                        <li class="nav-item">
                            <a class="nav-link" id="images-tab" data-toggle="tab"  href="#images"  aria-controls="images" aria-selected="false">Media</a>
                            </li>

                        <?php }
						if ($hasDocs >0 ) {?>
                        <li class="nav-item"><a class="nav-link" id="documents-tab" data-toggle="tab"  href="#documents"  aria-controls="documents" aria-selected="false">Information</a></li>
                        <?php
                            }
                        ?>
                        <li class="nav-item"><a class="nav-link" id="bibliography-tab" data-toggle="tab"  href="#bibliography"  aria-controls="bibliography" aria-selected="false">Bibliography</a></li>


                    </ul>

                    <div class="tab-content mh-100" id="idExptTabs">

                        <div class="tab-pane fade show active pb-3" id="overview" role="tabpanel"
                            aria-labelledby="overview-tab">
                            <?php
                    include '_summary.php';
                    ?>
                        </div>

                        <div class="tab-pane fade pb-3" id="design" role="tabpanel" aria-labelledby="design-tab">
                            <?php include '_design.php';?>
                        </div>

                        <div class="tab-pane fade pb-3" id="site" role="tabpanel" aria-labelledby="site-tab">
                            <?php include '_site.php';?>
                        </div>

                        <div class="tab-pane fade pb-3" id="datasets" role="tabpanel" aria-labelledby="datasets-tab">
                            <?php
$inDet = array(
                            "rbk1",
                            "rhb2",
                            "rpg5",
                            "rms",
                            "wms",
                            "bms"
                        );
if ($displayDatasets > 0) {
	
                        
						include '_datasets.php';
						echo  "<div class=\"mx-3\"><ul><li><a href=\"info/datasets\">List all eRA datasets</a></li><li>Additional data is available through <a class=\"btn btn-info m-1\" target=\"_BLANK\"
                        href=\"http://www3.rothamsted.ac.uk/cdera/extract/pages/data_extraction_prototype6.html\"><i
                            class=\"fa fa-list-alt\"></i> e-RAdata</a>. Please <a href=\"newGold.php\" >register for access</a></li></ul>.  </div>";
} else if ($displayDatasets == 0) {
						
                        
                        if (in_array($expt, $inDet)) {
                            echo  "<div class=\"mx-auto\" style=\"width: 600px;\">Additional data is available through 
                            <a class=\"btn btn-info m-1\" target=\"_BLANK\"
                            href=\"http://www3.rothamsted.ac.uk/cdera/extract/pages/data_extraction_prototype6.html\"><i
                                class=\"fa fa-list-alt\"></i> e-RAdata</a>. 
Please <a href=\"newGold.php\" >register for access</a>.  </div>
<div class=\"mx-auto\" style=\"width: 600px;\">
  <img class=\"mx-auto\" src=\"images/600x400/DETtop2021.jpg\" alt\"Extract data\" width=\"600\" height=\"400\">

</div>";
                        } else if ($expt == "rwf3") {
                            echo  "<div class=\"mx-auto\" style=\"width: 600px;\"> Datasets for Alternate Wheat and Fallow 
are only available through  <a class=\"btn btn-info m-1\" target=\"_BLANK\"
href=\"http://www3.rothamsted.ac.uk/cdera/extract/pages/data_extraction_prototype6.html\"><i
    class=\"fa fa-list-alt\"></i> e-RAdata</a>. Please <a href=\"newGold.php\" >register for access</a>. </div>
<div class=\"mx-auto\" style=\"width: 600px;\">
  <img class=\"mx-auto\" src=\"images/600x400/DETtop2021.jpg\" alt\"Extract data\" width=\"600\" height=\"400\">

</div>
";
                        } else {
                    
                        echo  "<div class=\"mx-auto\" style=\"width: 600px;\">There are currently no prepared datasets online for this experiment. However, 
there may still be data available but requiring curation. 
For more information please <a href=\"mailto:era@rothamsted.ac.uk\">contact the e-RA curators</a>. </div>
<div class=\"mx-auto\" style=\"width: 600px;\">
  <img class=\"mx-auto\" src=\"images/600x400/sortingsamples.jpg\" alt\"Working on it\" width=\"600\" height=\"400\">

</div>";
                        }
                    }
                    ?>
                        </div>


                        <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images-tab">
                            <?php if ($hasMedia) {
                                include $fileMedia;
                            } ?>
                            <?php include '_images.php';?>
                        </div>
                        <?php if ($hasDocs>0) {?>
                        <div class="tab-pane  pb-3" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                            <div class="mx-3">

                                <?php


   /* include $fileDocs;

    if (isset($sub)) {
        $docpage = $exptFolder . '/' . $sub . '.html';
        echo("<hr />");
        include $docpage;
    }
*/
    
 include('_information.php');
    ?>
                            </div>
                        </div>
                        <?php }?>
                        <div class="tab-pane fade pb-3" id="bibliography" role="tabpanel"
                            aria-labelledby="bibliography-tab">
                            <div class="mx-3">
                                <?php

                    include '_keyrefs.php';

                    ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
                
                
                // -- start footers ----------------------------
                include_once 'includes/footer.html';
                ?>

        <?php
                
                include_once 'includes/finish.inc'; // this has the common js scripts

                ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
        <script>
            baguetteBox.run('.compact-gallery', {
                animation: 'slideIn',
                captions: function (element) {
                    return element.getElementsByTagName('img')[0].alt;
                }
            });
        </script>


        <script>
            $(document).ready(() => {
                let url = location.href.replace(/\/$/, "");

                if (location.hash) {
                    const hash = url.split("#");
                    $('#Expttabs a[href="#' + hash[1] + '"]').tab("show");
                    $('li.active').removeClass('active');
  $('a[href="#' + hash[1] + '"]').closest('li').addClass('active'); 

                    url = location.href.replace(/\/#/, "#");
                    history.replaceState(null, null, url);
                    setTimeout(() => {
                        $(window).scrollTop(0);
                    }, 400);
                }

                $('a[data-toggle="tab"]').on("click", function () {
                    let newUrl;
                    const hash = $(this).attr("href");
                    if (hash == "#home") {
                        newUrl = url.split("#")[0];
                    } else {
                        newUrl = url.split("#")[0] + hash;
                    }
                    newUrl += "/";
                    history.replaceState(null, null, newUrl);
                });
            });
        </script>
    </div>
</body>

</html>