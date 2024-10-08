<?php
/**
 * @file dataset.php
 * @brief Dataset page
 *
 * This is a page: so it includes modules: here the _dataset.php module
 *
 * @author Nathalie Castells
 * @date 9/27/2018
 * 
 * TODO: add contributors
 */
include_once 'includes/init.php'; // these are the settings that refer to more than one page

$datasetTitle = "";
$dateCreation = "";
$datePublication = "";
$dateUpdate = "";
$getAuthors = "";
$getAuthorOrganisation = "";
$getContributors = "";
$hasCT = 0;
$getPublisher = "";
$isAuthor = 0;
$refAuthor = "DEFAULT";
$onload = "";
$actualURL = "";
$strDownload = "";
$strMeta = "";
$strUserArea = "";
$zipfile = "";
$pageinfo = getPageInfo($expt);
$KeyRef = $pageinfo['KeyRef'];
$page_title .= ' dataset: ' . $dataset;
$page_description = "";
$page_keywords .="";
$datasetParts = explode("-", $dataset);
$pageinfo = getPageInfo($expt);
$KeyRef = $pageinfo['KeyRef'];
$exptFolder = 'metadata/' . $expt;
$fileDataset = $exptFolder . '/' . $datasetParts[1] . '/' . $dataset . '.json';
$dsinfo = "";

$hasDataset = file_exists($fileDataset);
if ($hasDataset) {
    $jdataset = file_get_contents($fileDataset);
    $jdataset8 = utf8_encode($jdataset);
    $dsinfo = json_decode($jdataset8, true);
    $page_description = htmlentities($dsinfo['description'][0]['description']);
    $page_keywords .= htmlentities(implode(' , ', $dsinfo['keywords']));
}
if (isset($_SESSION['visitedPages'])) {
    $_SESSION['visitedPages'][] = array(
        "dataset" => $dataset,
        "expt" => $expt
    );
} else {
    $_SESSION['visitedPages'] = array();
    $_SESSION['visitedPages'][] = array(
        "dataset" => $dataset,
        "expt" => $expt
    );
}
$_SESSION['page'] = 'dataset/' . $expt . '/' . $dataset;
$_SESSION['dataset'] = $dataset;
$_SESSION['expt'] = $expt;

function printVocab($localwords)
{
    $strvocab = "";
    foreach ($localwords as $localword) {
        $slug = str_replace(' ', '-', $localword);
        $vocab = "<a href=\"keyword/".$slug."\">".$localword."</a>";
        $strvocab .= $vocab . " - ";
    }
    $strvocab = substr_replace($strvocab, " ", - 2); // removes the last -
    return $strvocab;
}

$relDatasets = 'List of Related Datasets';
$relDocuments = 'List of Related Documents';

if ($hasDataset) {
    $datasetFolder = $dsinfo["shortName"];
    $datasetTitle = $dsinfo['name'];
    if (strstr(strtolower($datasetTitle), 'dataset')) 
    {
        // title remains the same
    }
    else 
    {
        $datasetTitle = 'Dataset: '. $dsinfo['name'];

    }
    $DOI = $dsinfo["identifier"];
    $actualURL = "";
$page_title =  'eRA Dataset: '.  $dataset. ' - '. $dsinfo['name'];
    //$testmbsubstring = substr($DOI, 0,3);
    if (substr($DOI, 0, 3) == "10.") {
        $actualURL = "https://doi.org/".$DOI; 
    }
    else {
        $actualURL = $dsinfo["url"];
    }

    if (isset($_SESSION['downloads'])) {
        if (in_array($DOI, $_SESSION['downloads'])) {
            $strUserArea .= "<li class=\"list-group-item\">Downloaded today</li>";
        }
    }
    // this decide what modal we give depending on the type of dataset: the OA at the moment, does not record the download.

    $dstype = $dsinfo["dstype"];
    $dstypeModal = $dsinfo["dstype"];
    $dstypeStr = "<a href=\"info/howtoaccessdata#OA\" \" target=\"out\">Open</a>";
    if ($dsinfo["dstype"] != 'OA') {
        $dstypeStr = "<a href=\"info/howtoaccessdata#Other\" \" target=\"out\">Registration</a>";
        $dstypeModal = 'Other';
    }

    //$modal = "#modalClickTrough" . $dstypeModal;
    $modal = "#modalClickTroughOA";
    $butDownload = "<button type=\"button\" class=\"btn btn-primary my-1 mx-3\" data-toggle=\"modal\"
    data-target=\"" . $modal . "\">Download Data</button>";

    /*     $butLogin = "<button type=\"button\" class=\"btn btn-info my-1 mx-3 \" data-toggle=\"modal\"
        data-target=\"#modalLogin\">" . $registeredUser . "</button>"; */

    if ($dsinfo["dateCreated"]) {
        $dateCreation .= "<li class=\"list-group-item\"><b>Created: </b> ".$dsinfo["dateCreated"]."</li>";
    } else {

    } 

    if ($dsinfo["datePublished"]) {
        
        $datePublication .= "<li class=\"list-group-item\"><b>Published: </b> ".$dsinfo["datePublished"]."</li>";
    } else {
        
    }
    $dateUpdate = "";
    if ($dsinfo["dateModified"]) {
        $dateUpdate .= "<li class=\"list-group-item\"><b>Updated: </b> ".$dsinfo["dateModified"]."</li>";
    } else {
        
    }

    if (is_array($dsinfo['creator'])) {
        foreach ($dsinfo['creator'] as $creator) {
            if ($creator['type'] == 'organization') {
                $getAuthorOrganisation .= $creator['name'] . ", ";
            }
            if ($creator['type'] == 'Person') {
                $getAuthors .= $creator['Name'] . ", ";
                $isAuthor = 1;
            }
        }
    }

    if (is_array($dsinfo['contributor'])) {
        $tblContributors = "     \n   <h3>Contributors</h3><ul>";
        foreach ($dsinfo['contributor'] as $contributor) {
            $hasCT = 1;
            if ($contributor['type'] == 'Person') {
                $getContributors .= $contributor['name'] . ", ";
                $strRole = preg_replace('/([a-z])([A-Z])/', '${1} ${2}', $contributor['jobTitle']);
                $strRole = strtolower($strRole);
                $strRole = ucfirst($strRole);
                $tblContributors .= "\n\t<li><b>" . $contributor['name'] . ": </b> " . $strRole."</li>";
            }
        }
        $tblContributors .= "</ul>";
    }

    $getAuthorOrganisation = rtrim($getAuthorOrganisation, ', ');
    $getAuthors = rtrim($getAuthors, ', ');
    $getContributors = rtrim($getContributors, ', ');

    if (is_array($dsinfo['publisher'])) {
        $getPublisher = $dsinfo['publisher']['name'];
    }

    $refAuthor = $getAuthorOrganisation;
    if ($isAuthor > 0) {
        $refAuthor = $getAuthors;
    } 

    $year = "" . $dsinfo["publication_year"];
    $risfile = $exptFolder . "/" . $datasetFolder . "/" . $dataset . ".ris";
    $enwfile = $exptFolder . "/" . $datasetFolder . "/" . $dataset . ".enw";
    
    if (is_array($dsinfo['distribution'])) {
        $zipfile = $exptFolder . "/" . $datasetFolder . "/" . $dataset . ".zip";

        if (file_exists($zipfile)) {
            $distribution = "<ul>";
            foreach ($dsinfo['distribution'] as $filedownloads) {

                $distribution .= "<li>";
                // $distribution .="<a href=\"" . $exptFolder . "/" . $datasetFolder . "/" . $filedownloads['URL'] . "\">";
                $distribution .= $filedownloads['name'] . " (" . $filedownloads['encodingFormat'] . ")";
                // $distribution .= "</a>";
                $distribution .= "</li>";
            }
            $distribution .= "</ul>";
        } else {
            $strDownload = "";
            $distribution = "In preparation";
        }
    }

    if (is_array($dsinfo['image'])) {

        $illustration = "";
        foreach ($dsinfo['image'] as $imgsrc) {

            $illustration .= "<div class=\"text-center\"><img src=\"";
            // $distribution .="<a href=\"" . $exptFolder . "/" . $datasetFolder . "/" . $filedownloads['URL'] . "\">";
            $illustration .= $exptFolder . "/" . $datasetFolder . "/" . $imgsrc['URL'] . " \" class=\"img-fluid\" alt=\"" . $imgsrc['caption'] . "\">";
            $illustration .= "<p><b><i>" . $imgsrc['caption'] . "</i></b></p></div>";
            // $distribution .= "</a>";
        }
    }
    // added 2024-02-07 - to add the extra funders 
    if (is_array($dsinfo['funding'])) {
        $countFunders = 0;
        // assuming we can find etra funders, we start the text for them, A header, then a UL
        $strFunders = "<h3 class=\"m-3\">Additional Funding sources</h4> 
        <p  class=\"m-3\">This project also received funding from the following sources<p>
        <ul>";

        // -  this lists the 3 or 4  which are already harcoded within the text. They don't need to be listed again 
        // - N/A represents LAT until something better comes up.
        // - Because the json file does not have teh id for the funder, (like 1, 2, 7, 14) we use the alternameName which is teh grant number
        // - Because we use strpos > 0 whatch out that the string we are looking for is not at the start of the altername name. So we look for /A not N/A ! 
        $default = array("000J0300","00005189", "23NB0007", "/A" );
        foreach ($dsinfo['funding'] as $funder) {
            $isDefault = 0;
            //Lets find out is the funder in hand is a default one or not. 
            foreach ($default as $item){
                if (strpos($funder['alternateName'], $item ) > 0 ) {
                    //found it
                     $isDefault += 1;
                } 
            }
            if ($isDefault == 0 ) {
                //we can add this info to the string as this is not found in the default list.
                /*
                  "funding": [{
            "type": "Grant",
            Title of the grant is: "name": "S2N - Soil to Nutrition Institute Strategic Programme (2017-2022)",
            Grant number is: "alternateName": "BBS/E/C/00010310",
            URL to the actual grant is: "url": "http://tinyurl.com/grant00010310",
            A text to replace all information is : "description": null,
            List of work packages is: "disambiguatingDescription": "BBS/E/C/00010310 (WP1)",
            this is the fuunding organisationL "funder": {
                "type": "organization",
                "name": "Biotechnology and Biological Sciences Research Council",
               The URL of the funder "url": "https://www.ukri.org/councils/bbsrc/",
               The DOi or ROR information for the funder:  "identifier": "https://doi.org/10.13039/501100000268"
            }
        },
                */
                if ($funder['description']) {
                    $strFunders .= "<li>".$funder['description']."<br />";
                    if ($funder['url']) {
                    $strFunders .= "<a target = \"_blank\" href=\"".  $funder['url'] . "\">  " . $funder['alternateName'] . "</a> <sup><i class=\"fa fa-external-link\" aria-hidden=\"true\"></i></sup> - ";
                    }
                    $strFunders .= (isset($funder['disambiguatingDescription']))? " - ". $funder['disambiguatingDescription'] : ""; 
                    $strFunders .=  "</li>";
                } else {
                    $strFunders .= "<li>".$funder['name']."  <a target = \"_blank\" href=\"".  $funder['identifier'] . "\">  " . $funder['alternateName'] . "</a> <sup><i class=\"fa fa-external-link\" aria-hidden=\"true\"></i></sup> - ".   $funder['disambiguatingDescription']  ." from <a href=\"".$funder['funder']['url']."\">".$funder['funder']['name']. "</li>";
                    }
                    $countFunders ++;
            }
            
        }
        $strFunders .= "</ul>";   
        //If we have not found any founders except the ones listed in default, then we reset the string to nothing 
        if ($countFunders == 0 )   {$strFunders = "";}  
    }
    
    if (is_array($dsinfo['relatedIdentifier'])) {
        $hasDatasets = 0;
        $hasDocuments = 0;
        $hasPVersion = 0;
        $hasNVersion = 0;
        $relDatasets_prev = "				<div class=\"card\">
					<div class=\"card-header\" id=\"Related\">
						<h5 class=\"mb-0\">
							<button class=\"btn btn-link collapsed\" data-toggle=\"collapse\"
								data-target=\"#collapseFive\" aria-expanded=\"false\"
								aria-controls=\"collapseFive\">Related Datasets</button>
						</h5>
					</div>
					<div id=\"collapseFive\" class=\"collapse\" aria-labelledby=\"Related\"
						data-parent=\"#accordion\">
						<div class=\"card-body\">";

        $relDatasets = "				<h3>Related Datasets</h3> <ul>";
        $prevVersions = "				<h3>Previous Versions</h3> <ul>";
        $newVersions = "				<h3>Newer Versions</h3> <ul>";
        $newVersionShort ="<ul>";
        $relDocuments_prev = "\n<div class=\"card\">
					\n<div class=\"card-header\" id=\"Supporting\">
						\n\t<h5 class=\"mb-0\">
						\n\t	<button class=\"btn btn-link collapsed\" data-toggle=\"collapse\"
								data-target=\"#collapseSupporting\" aria-expanded=\"false\"
								aria-controls=\"collapseSupporting\">Related Documents</button>
						\n\t</h5>
					\n\t</div>
				\n\t<div id=\"collapseSupporting\" class=\"collapse\"
						aria-labelledby=\"Supporting\" data-parent=\"#accordion\">
						\n\t<div class=\"card-body\">";

        $relDocuments = "\n<h3>Related Documents</h3> <ul>";

        foreach ($dsinfo['relatedIdentifier'] as $n => $ris) {
            switch ($ris['relatedIdentifierType']) {
                case "DOI":
                    $urlPrefix = "https://doi.org/";
                    break;
                case "URL":
                    $urlPrefix = "";
                    break;
                    break;
                case "PURL":
                    $urlPrefix = "";
                    break;
                case "ISBN":
                    $ris['relatedIdentifier'] = str_replace("-", "", $ris['relatedIdentifier']);
                    $urlPrefix = "https://isbnsearch.org/isbn/";
                    break;
            }
            if ($ris['relatedIdentifierGeneralType'] == "Text") {

                $hasDocuments = 1;

                $relDocuments .= "<li>  <a target = \"_blank\" href=\"" . $urlPrefix . $ris['relatedIdentifier'] . "\">  " . $ris['name'] . "</a> <sup><i class=\"fa fa-external-link\" aria-hidden=\"true\"></i></sup>: </li>";
            } elseif ($ris['relatedIdentifierGeneralType'] == "Dataset") {

                if ($ris['rt_id'] == 13) {
                    $hasPVersion = 1;
                    $prevVersions       .= "\n<li> " . $ris['relationTypeValue'] . ": <a  href=\"" . $urlPrefix . $ris['relatedIdentifier'] . "\">  " . $ris['name'] . "</a></li>";
                } elseif ($ris['rt_id'] == 14) {
                    $hasNVersion = 1;
                    $newVersions        .= "\n<li>  <a  href=\"" . $urlPrefix . $ris['relatedIdentifier'] . "\">  " . $ris['name'] . "</a></li>";
                    $newVersionShort    .= "\n<li><a  href=\"" . $urlPrefix . $ris['relatedIdentifier'] . "\">  " . $ris['name'] . "</a></li>";
                } else {
                    $hasDatasets = 1;

                    $relDatasets        .= "\n<li>  <a  href=\"" . $urlPrefix . $ris['relatedIdentifier'] . "\">  " . $ris['name'] . "</a></li>";
                }
            } else {}
        }
        $relDatasets        .= "\n</ul>";
        $relDocuments       .= "\n</ul>";
     
        $prevVersions       .= "\n</ul>";
        $newVersions        .= "\n</ul>";
        $newVersionShort    .= "\n</ul>";
    }
    if (is_array($dsinfo['description'])) {
        $arrDescription = array();
        foreach ($dsinfo['description'] as $n => $description) {
            switch ($description['descriptionType']) {
                case "Abstract":
                    $arrDescription['Abstract'] = $description['description'];
                    break;
                case "Methods":
                    $arrDescription['Methods'] = $description['description'];
                    break;
                case "TechnicalInfo":
                    $arrDescription['TechnicalInfo'] = $description['description'];
                    break;
                case "TableOfContents":
                    $arrDescription['TableOfContents'] = $description['description'];
                    break;
                case "Provenance":
                    $arrDescription['Provenance'] = $description['description'];
                    break;
                case "Quality":
                    $arrDescription['Quality'] = $description['description'];
                    break;
                case "Other":
                    $arrDescription['Other'] = $description['description'];
                    break;
                default:
                    $arrDescription['Other'] = $description['description'];
            }
        }
    }
    /*
     * we check that the is downloading a file.
     * From the environment: we have everything we need about the file being downloaded and also the username of the user
     * On donload:
     * 1: make a SQL that writed in the usermanagment table that the user is downloading that dataset at that time
     */
    
    
    $link = LogMangaAd();
    $stmt = $link->prepare("INSERT INTO downloads (`position`, `IP`,  DOI, `dldate`,`dlresult`, fullname, country, information, institution) VALUES(?,?,?,?,?,?,?,?,?)");
    //$stmt -> bind_param("sssssssss", $positionValue, $IpAddress,  $DOI, $today, $dlresult, $fullname, $country, $information, $institution );
   

    $IpAddress = getIp();
    
    if (isset($_REQUEST['dlform'])) {
        $today = date('Y/m/d');
        if (!isset($_SESSION['user']['startSession'])) {
            $_SESSION['user']['startSession'] =  $today;
        
            $killdate = date_create($today);
            date_add($killdate , date_interval_create_from_date_string("7 days"));
            $_SESSION['user']['killSession'] =  date_format($killdate,"Y/m/d");
        }
        //$_SESSION['user']['killSession'] = '2023/01/01'; //testing so need to kill session everytime 
        $_SESSION['user']['lastDownload'] = $today;
        // Check the file exists or not
        if (file_exists($zipfile)) {
                // we are not collecting emails, but that could change
            if (isset($_REQUEST['InputEmail'])) {
                $positionValue = mysqli_real_escape_string($link, $_REQUEST['InputEmail']);
                $_SESSION['user']['positionValue'] = $positionValue;
            }
            $positionValue = "Anonymous";
            $fullname = "No Name";
            $institution  = "NONE";
            $information = "NONE";
            $country = "GB";
            $_SESSION['downloads'][] = $DOI;
            //we get it from the request and also put it in session! this might need to be done
            // if not from the request, we get it from session - if in the session, we do not ask again
            if (isset($_REQUEST['InputName'])) {
                $fullname = mysqli_real_escape_string($link, $_REQUEST['InputName']); 
                $_SESSION['user']['fullname'] = $fullname;   
            } elseif (isset($_SESSION['user']['fullname'])) {
                $fullname = $_SESSION['user']['fullname'];
            } 
            if (isset($_REQUEST['inputCountry'])) {
                $country = mysqli_real_escape_string($link, $_REQUEST['inputCountry']);
                $_SESSION['user']['country'] = $country;    
            } elseif (isset($_SESSION['user']['country'])) {
                $country = $_SESSION['user']['country'];
            } 
        
            if (isset($_REQUEST['information'])) {
                $information = mysqli_real_escape_string($link, $_REQUEST['information']);   
                $_SESSION['user']['information'] = $information; 
            } elseif (isset($_SESSION['user']['information'])) {
                $information = $_SESSION['user']['information'];
            }
            if (isset($_REQUEST['InputInstitute'])) {
                $institution = mysqli_real_escape_string($link, $_REQUEST['InputInstitute']);
                $_SESSION['user']['institution'] = $institution;    
            } elseif (isset($_SESSION['user']['institution'])) {
                $institution = $_SESSION['user']['institution'];
            }
            $dlresult = $siteType ;
            $stmt -> bind_param("sssssssss", $positionValue, $IpAddress,  $DOI, $today, $dlresult, $fullname, $country, $information, $institution );
            // blocking the favorite ROBOT from russia
            if ($IpAddress != "213.109.202.67") {
                if ($stmt->execute()) { $onload = " onload=\"modal();\" "; }
            }
            
            $strMeta .= " <meta
             http-equiv=\"refresh\"
                 content=\"1; URL=".$root . $zipfile . '?' . time()."\">"; // the Time function to avoid getting the file from the
        } else {
            //No file present - 
            $dlresult = "NO FILE" ;
            if ($stmt->execute())  {$strUserArea .= "<li>File Not Found - Team notified </li>"; }
        }
    }
    $strUserArea .= "";
    mysqli_close($link);

    if (file_exists($zipfile)) {  
            $strDownload = $butDownload;
    } else {
        $strDownload = "";
    }
    if ( $dsinfo["isExternal"] ) {$strDownload = "";}
} else {}

?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <?php
        echo $strMeta; 
        include 'includes/meta.html'; // that is the <meta and link tags> superseeds head.html
                                      // that is the <head tags>

        $script = ''; // $script is added to the header as the
        if (isset($jdataset8)) {
            $script = "<script type=\"application/ld+json\">" . $jdataset8 . "</script>";
            echo $script;
        }
        ?>
</head>

<body <?php echo $onload; ?>>
    <div class="container bg-white px-0">

        <?php

    include 'includes/header.html'; // all the menus at the top

    // -- start dependent content ---------------------------------------------------------

    include '_dataset.php';

    // -- start footers -----------------------------

    include_once 'includes/footer.html';

    // --------------- page specific scripts -------------
?>

        <?php
    // ---------------------------------------------------
    include_once 'includes/finish.inc';

    ?>