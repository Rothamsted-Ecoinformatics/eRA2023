<?php
/**
 * @file updates.php
 * 
 * @brief lists the updates and new datasets.
 *
 * @author Nathalie Castells.
 * 
 * @description If there are no experiment in the field expt, then the page lists all the updates. 
 * If there is an experiment in the field, then we list the updates for the experiment. 
 * Updates granularity: experiment. 
 */

include_once 'includes/init.php'; // these are the settings that refer to more than one page


/**
 * $page info should have the following structure:
 * array(4) {
 * ["ExperimentName"]=> string(9) "Broadbalk"
 * ["ExptCode"]=> string(4) "rbk1"
 * ["KeyRef"]=> string(15) "KeyRefBroadbalk"
 *
 * ["URLCode"]=> string(9) "Broadbalk" }
 * @var array $pageinfo
 */

$h1Title = 'News and Updates';
$page_title .= ' News and Updates';
// $experimentName = '' // fill that in and uncomment if there is no experimentName
// /This is used in the head file as the title tag
// /$arrdatacite is found in $exptDescriptionFile - describes the submission to Datacite - necessary for DOI pages

?>
<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>  
        <?php
        include 'includes/meta.html'; // that is the <meta and link tags> superseeds head.html
        
        ?>  
    </head>
<body>
	<div class="container bg-white px-0">

<?php

include 'includes/header.html'; // all the menus at the top
                                
// -- start dependant content ---------------------------------------------------------
?>

<div id="greenBar"
	class="d-flex justify-content-between  py-3 p3-3 bg-info text-white mt-0 ">
    <div  class="px-3" ><h1>News and Updates</h1>
    </div>
	<div class="px-3">

	
		<a target="_blank"  class="btn  bg-primary text-white  "  
			href="https://twitter.com/eRA_Curator"> <i class="fa-brands fa-x-twitter "></i>
		</a> 

	</div>
</div>
<div id="renameIDToSomethingRelevant" class="mt-5">
<?php 
if ($displayValue == 0) {
    ?>
    <div class="d-flex justify-content-between bg-warning text-white p-3 "> 
        <h2>To edit this page go to <u>'//INTRANET-SERVER/../era2023/metadata/default/news.html</u> then run your personalized update-NAME.exe tool.</h2>
    </div>
    <?php
    }
include 'metadata/default/news.html'; 
?>

</div>
					
<?php
// -- start footers -----------------------------

include_once 'includes/footer.html'; // this has the green bar and bottome stu

?>
 
	</div>
<?php

include_once 'includes/finish.inc'; // this has the common js scripts

?>
<!--  include here the page dependant scripts -->
</body>

</html>

