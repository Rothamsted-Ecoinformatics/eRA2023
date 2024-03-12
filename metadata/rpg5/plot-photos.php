<?php
/**
 * @file metadata/rpg5/plot-photos.php
 * @brief page that shows the plot photos grouped and ordered according to treatments. 
 * Based on baguette box, 
 * It uses the dataset in rpg5/plotPhots
 * 
 * i
 * When using this module: remember to include the baguetteBox at the bottom of the page and also the baguetteBox.css in the header. - that should be there 
 * See site.php for example. 
 
 * @author Nathalie Castells
 * @date 16/11/2022
 * 
 * The dataset is made into a json file using the usual data converter. 
 * Sort the dataset by group, plot, subplot before conversion
 */
$expt = 'rpg5';
if (!isset($expt)) {$expt = $farm;}


/**
 * Function ListImages
 * translate the metadata array into the gallery of images
 *
 * The gallery uses BaguetteBox which must be included in the pages that use that function.
 * Also usees the CSS in style-cg.css. So make sure that is also included in the page that uses that function.
 * see farm.php for example of usage.
 *
 * @author Nathalie Castells
 * @version 0.1
 * @param array $arrdata
 *            The result (numericaly keyed, associative inner) array.
 *            @output returns a string to display the data
 *            
 */
function listPlotImages($arrdata)
{
  /*
   {
        "imageID": 63,
        "groupID": 2,
        "group": "Ammonium N group",
        "plot": "9-1",
        "sub-plot": "c",
        "treatment": "(N2)PKNaMg since 1990",
        "lime-to-pH": "5",
        "filename": "9-1c.jpg",
        "date": "09/06/2022",
        "plant_height": 225,
        "species_numbers": 18,
        "width": 300,
        "height": 400,
        "orientation": "Portrait",
        "ExptID": "rpg5/plot-photos",
        "note_id": null
    },
*/
    
    $startUL = "<section class=\"gallery-block compact-gallery\">";
	$startUL .= "<div class=\"row d-flex justify-content-center\">";
    
    $startUL .= "<div class=\"col-2 text-center\"></div>";
    $startUL .= "<div class=\"col-2 text-center\"><h3>pH = 7</h3></div>";
    $startUL .= "<div class=\"col-2 text-center\"><h3>pH = 6</h3></div>";
    $startUL .= "<div class=\"col-2 text-center\"><h3>pH = 5</h3></div>";
    $startUL .= "<div class=\"col-2 text-center\"><h3>no Lime </h3></div>";

    
    $list = $startUL;
    $plot = "";
    $groupID= 0;
    $textpH = "";
    for ($j = 0; $j < count($arrdata); $j ++) {
        
        if ($arrdata[$j]['plot'] != $plot) {
            $plot = $arrdata[$j]['plot'];
            $list .= "</div>"; //finish previous row
            $list .= "<div class=\"row d-flex justify-content-center \">"; //start next row
            if ($arrdata[$j]['groupID'] != $groupID) {
                $groupID = $arrdata[$j]['groupID'];
                //we add a header for the group
                
                $list .= "<div class=\"col-sm-4 col-md-4 col-lg-4 item zoom-on-hover \"> \n";
                $list .= "<h2> ".$arrdata[$j]['group'] ."</h2>";
        $list .= "</div> \n";
        $list .= "<div class=\"col-sm-8 col-md-8 col-lg-8 item zoom-on-hover \"> \n";
        $list .= "</div> \n"; 
        $list .= "</div> \n"; 
        $list .= "<div class=\"row d-flex justify-content-center\">";
            } else {}

            $list .= "<div class=\"col-sm-2 col-md-2 col-lg-2 item zoom-on-hover \"> \n";
        $list .= "<h4>Plot: ".$arrdata[$j]['plot'] ."</h4><h4> Treatment: ". $arrdata[$j]['treatment'];
        $list .= "</h4></div> \n"; // first column is the treatment
    
        }
        if ($arrdata[$j]['lime-to-pH']== "No Lime") {
            $textpH = " - No Lime";
        }
        else 
        {
            $textpH = " - Lime to pH ".$arrdata[$j]['Lime-to-pH'];
        }
        $caption = "Plot ".$arrdata[$j]['plot'] . $arrdata[$j]['sub-plot'] ." - ". $arrdata[$j]['treatment'].$textpH;
        $caption .= " - ".$arrdata[$j]['species_numbers']  ." species -   height of plants  ". $arrdata[$j]['plant_height']." mm ";
        if ($arrdata[$j]['lime-to-pH']== "void") {
            $textpH = " - No Lime";
            $caption = "";
        }
        $urlThumb = "metadata/rpg5/plotphotos/plot-photos/" . $arrdata[$j]['filename'] ;
        $url = "images/metadata/rpg5-plot-photos/" . $arrdata[$j]['filename'] ;

        $list .= "<div class=\"col-sm-2 col-md-2 col-lg-2 item zoom-on-hover \"> \n";
        $list .= "<a class=\"lightbox \" href=\"" . $url . "\"> \n";
        $list .= "<img alt=\"" . $caption."\"
                        class=\"img-fluid image thumbnail \" \n
                        src=\"" . $urlThumb . "\" \n
                        >";
        $list .= "<span class=\"description\">";
        $list .= "<span class=\"description-heading\">" .$caption  . "</span> 
        </span>
        </a>
        </div>";
    }
    $list .= "</div>"; // finishes the last row
	
    $list .= "</section>"; // finishes the section
    return $list;
}

$imageurl =  'metadata/rpg5/plotphotos/plot-images.json';
if (is_file($imageurl)) {
    $idata = file_get_contents($imageurl);
    $images = json_decode($idata, true);

    
    //obtain a list of columns
    //$caption  = array_column($images, 'Caption');
    //$orientation= array_column($images, 'orientation');
    
    // Sort the data with orientation descending, caption ascending
    // Add $images as the last parameter, to sort by the common key
    //array_multisort($orientation, SORT_DESC, $caption, SORT_ASC, $images);
 
    $info = listPlotImages($images);
    
   
    
} else { echo ("Hmmm ");}
?>


<p>Park Grass Long-term Experiment is on a permanent un-grazed grassland. Since 1856 its various plots have received differing fertilizer and manure treatments and from 1903 sub-plots have received lime to adjust the pH of the soils. These differing treatment applications have resulted in dramatic differences in species composition, in what had previously been a uniform sward.</p>
<p>Read more and download this dataset: <a href="https://doi.org/10.23637/rpg5-plotphotos-01">https://doi.org/10.23637/rpg5-plotphotos-01</a></p>
<p>Click on individual images below to view and download selected sub-plot photos</p>
<?php
        echo $info;

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