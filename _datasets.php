<?php
/**
 * @file _datasets.php
 * @brief lists datasets for an experiment
 *Lists datasets for an experiment. This module is very simple and therefor could be dropped in favor of just using the function it calls. 
 *This module is for use in the expt page. It needs information contained in the data-description file.
 * @author Nathalie Castells
 * @date 9/27/2018
 */
?>



<?php
# We will be printing $list at the end - there might be some subpart along the way! 
$list = "<h2 class=\"mx-3\">Datasets available</h2>"; # the one we print at the end

$info = ""; 
if (! $hasDatasets) {
    $list .= "<p>There are no datasets for this experiments yet. </p>";
} 
else 
{  
    $prefix = "10.23637/";
    /*
     * the function group_by then also sort them
     */

    # obtain a list of columns
    $years  = array_column($datasets, 'publication_year');
    $shortname= array_column($datasets, 'shortName');
    $version= array_column($datasets, 'version');
    $grades= array_column($datasets, 'grade');
 
    // Sort the data with orientation descending, caption ascending
    // Add $images as the last parameter, to sort by the common key
    array_multisort($grades, SORT_DESC, $shortname, SORT_ASC, $version, SORT_DESC, $datasets);

    $gpDS = group_by('dataset_type', $datasets);

    $list .= "\n<div class=\"table-responsive-sm mx-3 rounded p-3 mb-3\">";
    $list .= "\n\n<table class = \"table  table-responsive-sxm table-sm bg-white table-bordered  table-condensed\">";
    $list .= "\n<thead class=\"thead-light\">\n\t<tr>";
    
    $list .= "\n\t\t<th scope=\"col\">Title <small>(hover for a longer description)</small></th>";
    $list .= "\n\t\t<th scope=\"col\">Year of Publication</th>";

    $list .= "\n\t\t<th scope=\"col\">Identifier</th>";
    $list .= "\n\t\t<th scope=\"col\">Version </th>";
    $list .= "\n\t</tr>\n</thead>\n<tbody>";

    foreach ($gpDS as $groupName => $groupedDatasets) {
        
        $notEmptyGr = 0;
        $listGr = "<tr>";
        $listGr .= "\n    \t     \t <td colspan=\"4\" class=\"pr-4 \">\n\t<h3 class=\"mt-3 text-primary\">\n\t".$groupName."</h3></td>";
        $listGr .= "</tr>";

        
        foreach ($groupedDatasets as $dataset) {
    
            # each dataset will be either ready of not
            # a ready dataset will have the identifier only and link to the DOI or url - if the identifier is a DOI: link the DOI - else link the url
            # a draft dataset will have a link to the draft version on the local site - relative and a disabled DOI link as it won't be minted;
            # a draft dataset will also have a ORANGE warning that the dataset is in DRAFT
            # all that will be working seaminglessly. 

            # the dataset is ready and minted
            if ($dataset['isReady'] >  $displayValue) {
                $notEmptyGr = 1;
                if ($dataset['UID']) {
                    $fileDataset = $exptFolder . '/' . $dataset['shortName'] . '/' . $dataset['UID'] . '.json';
                } else {
                    $fileDataset = $exptFolder . '/' . $dataset['shortName'] . '/' . $dataset['shortname'] . '.json';
                }
                $subDescription = '';
                
                $subDescription = niceChop($dataset['description'], 200);
                
                $id = str_replace($prefix, '', $dataset['identifier']);
                $shortname = $dataset['shortName'];
                $dstype = "Summary";
                if ($dataset['dstype']=="Frictionless") {$dstype = "Complete (Frictionless)";}
                
                $countVersions = array_count_values(array_column($datasets, 'shortName'))[$shortname];
                if ($countVersions < 10) {
                    $strCount = "0" . strval($countVersions);
                } else {
                    $strCount = strval($countVersion);
                }

                $info  = "\n    \t  <tr>";
                
                $info .= "\n    \t     \t <td class=\"px-4 \"><b>".$dataset['title']." </b><i class=\"bi bi-file-text\"  title=\"". $subDescription   ."\">
                <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-file-text\" viewBox=\"0 0 16 16\">
                <path d=\"M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z\"/>
                <path d=\"M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z\"/>
              </svg></i>";
              $datasetCheckURL = "DRAFT VERSION";
              if ($dataset['isReady'] == 1  ) {
                $info .= $checkThisOne;
                $datasetCheckURL = "<span class=\"text-warning\">DRAFT VERSION: </span>" ;
                if (substr($dataset['identifier'],0,3)=== '10.') {
                    $identifierLink = "https://doi.org/". $dataset['identifier']; # DOI not minted so no link
                    } else {
                    $identifierLink =  $dataset['url']." - " .$dataset['identifier'];   
                    }
                }
                    else 
                {
                    if (substr($dataset['identifier'],0,3) === '10.') {
                    $identifierLink =  "<a  href=\"https://doi.org/". $dataset['identifier']."\">https://doi.org/". $dataset['identifier']."</a>"; # DOI not minted so no link
                    } else {
                    $identifierLink =  "<a  href=\"dataset/".$expt."/". $dataset['version']."-".$dataset['shortName'] . "\">".$expt."/". $dataset['version']."-".$dataset['shortName'] . " - ". $dataset['identifier']."</a>";;   
                    }
                }
                $info.="</td>";
                $info .= "\n    \t     \t <td class=\"pr-4 \">".$dataset['publication_year']."</td>";
                
                $info .= "\n    \t     \t <td class=\"pr-4 \">".$identifierLink."<br />";
               
                # if the site is intranet - displayvalue is 0 then we need a link to the draft version 
                if ($displayValue == 0) { 
                $info .= $datasetCheckURL;
                $info .= "\n    \t     \t <a  href=\"dataset/".$expt."/". $dataset['version']."-".$dataset['shortName'] . "\">".$expt."/". $dataset['version']."-".$dataset['shortName'] . "</a>";
                }
                $info .= "\n    \t     \t</td>";
                $info .= "\n    \t     \t <td class=\"pr-4 \">". $dataset['version']."</a></td>";
                $info .= "\n    \t  </tr>";
                
                /*
                $info .= "\n	<div class=\"card  h-100 bg-light mb-3 \" >";
                
                $info .= "\n	\t	<div class=\"card-header\">" . $dataset['title'] . "</div>";
                $info .= "\n	\t	<div class=\"card-body\">";
                if ($dataset['isReady'] == 1  ) {
                    $info.="<B>CHECK THIS ONE</B><br />";
                }
                // $info .="\n \t <h4 class=\"card-title\">Light card title</h4>";
                $info .= "\n	\t	\t		<small class=\"card-muted\">" . $dataset['dataset_type'] . $subDescription . " <br /> " . $dataset['shortName'] . " </small>";
                $info .= "\n	\t		</div>";
                $info .= "\n	\t	<div class=\"card-footer\"> <a class=\"btn btn-primary stretched-link\" href=\"dataset/" . $expt . "/" . $dataset['UID'] . "\"> More ...</a></div>";
                
                $info .= "\n	\t	</div>";
                $info .= "\n	\t	</div>";
                /*
                 * now if the dataset has a next version, then do not show
                 */
                
                 //if ($strCount == $dataset['version']) {
                    $listGr .= $info;
                    $notEmptyGr = 1; // shift group to
                 //}
                
            }
        }

        
        if ($notEmptyGr == 1) {
            $list .= $listGr;
            
        }
    }
    $list .= "</table></div>";
    
}

?>

<?php echo  $list ?>