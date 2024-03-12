<?php 
/**
 * $pageSettings is made using the Experiments Table in timelines.accdb and converted to array.
 *
 * In this array, we can fetch Page Titles, and location of information and Key refs.
 *
 * @todo when a new experiment or dataset is provided, please update this array.
 *      
 * @var array $pageSettings
 * 
 * Pagesettings associates the expt code, or station with the keyrefs, the page titles and so on. 
 * It started with using the experiment table, but now I think we have gone beyond that and I need to check
 * what is using this array and where it actually should be used and what variables are never used. For example, 
 * I don't think we can use exp-ID because I cannot maintain uniqueness if it is manually maintained, 
 */
$pageSettings = array(
    array(
        "exp-ID" => 23,
        "Experiment" => "Update Page Settings",
        "ExptCode" => "000",
        "KeyRef" => " - ",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 23,
        "Experiment" => "Broom's Barn Large Scale Rotation Experiment",
        "ExptCode" => "bbcs788",
        "KeyRef" => "KeyRefLSRE",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 24,
        "Experiment" => "Large Scale Rotation Experiment",
        "ExptCode" => "bbrcs788",
        "KeyRef" => "KeyRefLSRE",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 24,
        "Experiment" => "Brooms Barn Met Station",
        "ExptCode" => "bms",
        "KeyRef" => "KeyRefBMS",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 7,
        "Experiment" => "Broom's Barn",
        "ExptCode" => "broomsbarn",
        "KeyRef" => "KeyRefBB",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 8,
        "Experiment" => "General Information",
        "ExptCode" => "default",
        "KeyRef" => " - ",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 9,
        "Experiment" => "eRA Data extraction Tool",
        "ExptCode" => "DET",
        "KeyRef" => "KeyRefDET",
        "URLCode" => "DET",
        "prevFolder" => "DET"
    ),
    array(
        "exp-ID" => 40,
        "Experiment" => "Acid strip experiment",
        "ExptCode" => "rrs9",
        "KeyRef" => "KeyRefAcidStrip",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 10,
        "Experiment" => "Weather Data",
        "ExptCode" => "met",
        "KeyRef" => "KeyRefMetdata",
        "URLCode" => "Met",
        "prevFolder" => "met"
    ),
    array(
        "exp-ID" => 11,
        "Experiment" => "North Wyke farm Platform",
        "ExptCode" => "northwyke",
        "KeyRef" => " - ",
        "URLCode" => "",
        "prevFolder" => "NorthWyke"
    ),
    array(
        "exp-ID" => 12,
        "Experiment" => "Other experiments",
        "ExptCode" => "others",
        "KeyRef" => " - ",
        "URLCode" => "Other",
        "prevFolder" => "others"
    ),
    array(
        "exp-ID" => 33,
        "Experiment" => "Agdell",
        "ExptCode" => "rag6",
        "KeyRef" => "KeyRefAG",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 1,
        "Experiment" => "Broadbalk",
        "ExptCode" => "rbk1",
        "KeyRef" => "KeyRefBroadbalk",
        "URLCode" => "Broadbalk",
        "prevFolder" => "broad"
    ),
    
    array(
        "exp-ID" => 1,
        "Experiment" => "Broadbalk Wilderness",
        "ExptCode" => "rbk1w",
        "KeyRef" => "KeyRefWilderness",
        "URLCode" => "Broadbalk",
        "prevFolder" => "broad"
    ),
    array(
        "exp-ID" => 32,
        "Experiment" => "Barnfield",
        "ExptCode" => "rbn7",
        "KeyRef" => "KeyRefBF",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 30,
        "Experiment" => "Rothamsted Long Term Liming",
        "ExptCode" => "rcs10",
        "KeyRef" => "KeyRefliming",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 41,
        "Experiment" => "ECN plots, Rothamsted and North Wyke",
        "ExptCode" => "rcs109",
        "KeyRef" => "KeyRefECN",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 100,
        "Experiment" => " Rothamsted Sample Archive",
        "ExptCode" => "rsa",
        "KeyRef" => "KeyRefRSA",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 39,
        "Experiment" => "Continuous maize experiments",
        "ExptCode" => "rcs477, wcs478",
        "KeyRef" => " ",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 35,
        "Experiment" => "Highfield Conversion experiment",
        "ExptCode" => "rcs767",
        "KeyRef" => "KeyRefHR",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 6,
        "Experiment" => "Rothamsted Large Scale Rotation Experiment",
        "ExptCode" => "rcs790",
        "KeyRef" => "KeyRefLSRE",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 4,
        "Experiment" => "Exhaustion Land",
        "ExptCode" => "rex4",
        "KeyRef" => "KeyRefEX",
        "URLCode" => "Exhaustion",
        "prevFolder" => "exhaustion"
    ),
    array(
        "exp-ID" => 15,
        "Experiment" => "Garden Clover",
        "ExptCode" => "rgc8",
        "KeyRef" => "KeyRefGC",
        "URLCode" => "Garden",
        "prevFolder" => "clover"
    ),
    array(
        "exp-ID" => 22,
        "Experiment" => "Geescroft Wilderness",
        "ExptCode" => "rge9",
        "KeyRef" => "KeyRefWilderness",
        "URLCode" => "wilderness",
        "prevFolder" => "wilderness"
    ),
    array(
        "exp-ID" => 2,
        "Experiment" => "Hoosfield",
        "ExptCode" => "rhb2",
        "KeyRef" => "KeyRefHoosfield",
        "URLCode" => "Hoosfield",
        "prevFolder" => "hoos"
    ),
    array(
        "exp-ID" => 13,
        "Experiment" => "Rothamsted Research Station",
        "ExptCode" => "rothamsted",
        "KeyRef" => "KeyRefRothFarm",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 13,
        "Experiment" => "Rothamsted Weather Station",
        "ExptCode" => "rms",
        "KeyRef" => "KeyRefRMS",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 3,
        "Experiment" => "Parkgrass",
        "ExptCode" => "rpg5",
        "KeyRef" => "KeyRefParkGrass",
        "URLCode" => "Park",
        "prevFolder" => "pg"
    ),
    array(
        "exp-ID" => 21,
        "Experiment" => "Rothamsted Ley Arable",
        "ExptCode" => "rrn",
        "KeyRef" => "KeyRefRLA",
        "URLCode" => "RRN",
        "prevFolder" => "rrn"
    ),
    array(
        "exp-ID" => 19,
        "Experiment" => "Rothamsted Ley Arable: Highfield",
        "ExptCode" => "rrn1",
        "KeyRef" => "KeyRefRLA",
        "URLCode" => "RRN1",
        "prevFolder" => "rrn1"
    ),
    array(
        "exp-ID" => 20,
        "Experiment" => "Rothamsted Ley Arable: Fosters",
        "ExptCode" => "rrn2",
        "KeyRef" => "KeyRefRLA",
        "URLCode" => "RRN2",
        "prevFolder" => "rrn2"
    ),
    array(
        "exp-ID" => 34,
        "Experiment" => "Highfield Bare Fallow",
        "ExptCode" => "rrs1",
        "KeyRef" => "KeyRefHBF",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 31,
        "Experiment" => "Long Term Liming",
        "ExptCode" => "rwcs10",
        "KeyRef" => "KeyRefliming",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 38,
        "Experiment" => "Amounts of straw experiments",
        "ExptCode" => "rcs326",
        "KeyRef" => "KeyRefStraw",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 38,
        "Experiment" => "Amounts of straw experiments",
        "ExptCode" => "wcs326",
        "KeyRef" => "KeyRefStraw",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 5,
        "Experiment" => "Wheat and Fallow",
        "ExptCode" => "rwf3",
        "KeyRef" => "KeyRefWheatFallow",
        "URLCode" => "Alternate",
        "prevFolder" => "wheatfallow"
    ),
    array(
        "exp-ID" => 86,
        "Experiment" => "Woburn Continuous Barley",
        "ExptCode" => "wxb6",
        "KeyRef" => "KeyRefWCB",
        "URLCode" => "",
        "prevFolder" => ""
    ),
       array(
        "exp-ID" => 66,
        "Experiment" => "Woburn Continuous Wheat",
        "ExptCode" => "wxw6",
        "KeyRef" => "KeyRefWCW",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 14,
        "Experiment" => "Saxmundham",
        "ExptCode" => "saxmundham",
        "KeyRef" => "KeyRefSax",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 14,
        "Experiment" => "Saxmundham Weather Station",
        "ExptCode" => "sms",
        "KeyRef" => "KeyRefSMS",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 26,
        "Experiment" => "Saxmundham Rotation 1",
        "ExptCode" => "srn1",
        "KeyRef" => "KeyRefSaxRN",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 28,
        "Experiment" => "Saxmundham Rotation 2",
        "ExptCode" => "srn2",
        "KeyRef" => "KeyRefSaxRN",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 29,
        "Experiment" => "Woburn Long Term Liming",
        "ExptCode" => "wcs10",
        "KeyRef" => "KeyRefliming",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 42,
        "Experiment" => "Intensive cereals, Woburn",
        "ExptCode" => "wcs13",
        "KeyRef" => "KeyRefIntCereals",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 37,
        "Experiment" => "Woburn long-term sludge experiments",
        "ExptCode" => "wcs427, wcs428, wcs439",
        "KeyRef" => "KeyRefWobLTS",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 25,
        "Experiment" => "Wilderness Experiments",
        "ExptCode" => "wilderness",
        "KeyRef" => "KeyRefWilderness",
        "URLCode" => "",
        "prevFolder" => "wilderness"
    ),
    array(
        "exp-ID" => 16,
        "Experiment" => "Woburn Farm",
        "ExptCode" => "woburn",
        "KeyRef" => "KeyRefWobFarm",
        "URLCode" => "WoburnFarm",
        "prevFolder" => "Woburn"
    ),
    array(
        "exp-ID" => 16,
        "Experiment" => "Woburn Weather Station",
        "ExptCode" => "wms",
        "KeyRef" => "KeyRefWMS",
        "URLCode" => "WoburnFarm",
        "prevFolder" => "Woburn"
    ),
    array(
        "exp-ID" => 18,
        "Experiment" => "Woburn Organic Manuring",
        "ExptCode" => "wrn12",
        "KeyRef" => "KeyRefWOM",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    
    array(
        "exp-ID" => 18,
        "Experiment" => "Woburn Intensive Cereals",
        "ExptCode" => "wrn13",
        "KeyRef" => "KeyRefIntCereals",
        "URLCode" => "",
        "prevFolder" => ""
    ),
    array(
        "exp-ID" => 17,
        "Experiment" => "Woburn Ley Arable",
        "ExptCode" => "wrn3",
        "KeyRef" => "KeyRefWLA",
        "URLCode" => "WoburnLA",
        "prevFolder" => "woburnLA"
    ),
    array(
        "exp-ID" => 36,
        "Experiment" => "Woburn Market Garden experiment",
        "ExptCode" => "wrn4",
        "KeyRef" => "KeyRefWobMG",
        "URLCode" => "",
        "prevFolder" => ""
    )
);
