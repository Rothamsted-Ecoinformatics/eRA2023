<?php

/*
 * file: user.php
 * created 2/10/2020
 *
 *
 * This is where the application decided if the user is verified or no
 *
 * the Login only takes as email address. The database does not keep privatge information, so
 * there is no need for password.
 * The database keeps a record of it is registered or not,
 * it could also keep information of downloads of datasets and when
 * We need to ensure that this is open to the user.
 *
 * this has the functions to send the email too.
 * Because this may set cookies, it is read before any header are sent.
 *
 * $strRegister is either a form to send the email address or the email that is logged in folloed by sign out.
 * $strRegister = $formIN ;
 * $registeredUser = $email;
 * $registered = 'yes'
 *
 *
 * TODO: it would be nice if the curators only received the email when the email address is confirmed
 */

 /*------------------  constants  ----------------------------------*/
$redirect = "no"; 
$strMessage = '';
$output = "";
$registeredUser = "Get Link";
$registered = 'no';
$loggedIn = 'no';
$formOUT = "<div class=\"my-3\">
<form  action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\" name=\"registration-form\">
    
<div class=\"form-group\">
        <input type=\"email\" class=\"form-control\" id=\"email\" name=\"email\"
        placeholder=\"Enter email\" aria-describedby=\"emailHelp\">
        <small id=\"emailHelp\" class=\"form-text text-muted\">We'll never share your email with anyone else.</small>
    <div class=\"invalid-feedback\">Please enter a valid email address.</div>
    
</div>
    
    <button type=\"submit\" class=\"btn btn-primary float-right\"  name=\"UserForms\"
    class=\"g-recaptcha\" 
        data-sitekey=\"".$reCAPTCHA_site_key." \" 
        data-callback=\'onSubmit\' 
        data-action='login'>Get Link</button>
	</form>
</form>
</div>
	";

$strRegister = $formOUT;



/*-----------------------  Functions --------------------*/


/**
 * generate the confirmation email
 *
 * @param array $answers
 * @return string
 */
function buildemail($answers = array())
{
    global $Web_base;
    global $formOut;
    $to = $answers['email'];
    $process = $answers['process'];
    $subject = '[eRA]';
    if ($process == 'login') {
        $subject .= " Confirm your Email!";
    }
    if ($process == 'register') {
        $subject .= " Confirm your Email";
    }

    $message = "
<html>
<head>
<title>Confirmation email</title>
</head>
<body>
<P>Dear " . $answers['fname'] . " <br />
    
<br />
You, or someone pretending to be you has requested login or registration  into eRA.
<br />
";
    if ($process == 'register') {
        $message .= "
    <p>The following information was entered</p>
<ul>
<li>Name :  " . $answers['fname'] . " " . $answers['lname'] . "</li>
<li>Email address: " . $answers['email'] . "</li>
<li>Institution: " . $answers['institution'] . "</li>
<li>Country: " . $answers['country'] . "</li>
<li>Comment: " . $answers['information'] . "</li>";

//$message .= "<li>TEST: ". $recaptcha['success']. " - " . $recaptcha['score'] . " - " . $recaptcha['action']. "</li>";
//$message .= "<li>TEST: ".$recaptcha_file. "</li>";
$message .= "</ul>";
    }

    $message .= "
<p> 
        
<a  href=\"" . $Web_base . "index.php?process=confirm&VC=" . $answers['vericode'] . "&TC=" . $answers['timecode'] . "&VC2=" . $answers['vericode2'] . "\"> <b> Click to finish your login !</b>
			</a>
    </p>
    <p>            
  or paste the link into the browser you came from. " . $Web_base . "index.php?process=confirm&VC=" . $answers['vericode'] . "&TC=" . $answers['timecode'] . "&VC2=" . $answers['vericode2'] . "</p>              
                
 </p>               
<p><a href=\"mailto:unsubscribe.era@rothamsted.ac.uk?subject=unsubscribe\">Unsubscribe</a></p>             
</body>
</html>
";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: <era@rothamsted.ac.uk>' . "\r\n";
    $headers .= 'List-Unsubscribe: mailto:unsubscribe.era@rothamsted.ac.uk';
    if ($process == 'register') {
        $headers .= 'Cc: nathalie.castells@rothamsted.ac.uk' . "\r\n";
    }
    if (mail($to, $subject, $message, $headers)) {

        return "Please Check your mailbox for validation";
    } else {
        return "Unable to send mail";
    }
}

/**
 * function checkUser($info)
 * retrieves information about user from the database
 */
function checkUser($info)
{
    $link = LogMangaGuest();
    $info = cleanQuery($info);
    $query = "Select * from `newmarkers` where `position` LIKE '$info'";
    $results = mysqli_query($link, $query);

    if (! $results) {
        print("query failed");
        echo $query;
    } else {
        $i = 0;
        $nbResults = mysqli_num_rows($results);
        if ($nbResults == 1) {
            while ($row = mysqli_fetch_array($results)) {
                $user['email'] = $row['position'];
                $user['dbresponse'] = 'yes';
                $user['vericode'] = $row['vericode'];
                $user['fname'] = $row['fname'];
                $user['lname'] = $row['lname'];
                $user['institution'] = $row['institution'];
                $user['information'] = $row['information'];
                $user['allowEmails'] = $row['allowEmails'];
                $user['country'] = $row['country'];
            }
        } else {
            $user['dbresponse'] = 'no';
        }
    }

    return $user;
}


function formIN($email) {
    $formIN = "<div class=\"mt-3\">
    <form  action=\"" . $_SERVER['PHP_SELF'] . "\" method=\"post\">
    <div class=\"form-group\">
            <input type=\"hidden\" name=\"email\" value=\"delete\">
            <input type=\"hidden\" name=\"delete\" value=\"delete\"> You are logged in as:
            " . $email . "
    <br /></div>
    <button type=\"submit\"  class=\"btn btn-primary\" name=\"UserForms\">Log out!</button>
                
        </form>
        </div>";
        return $formIN;
    }

/**
 * generated any length random string
 *
 * @param number $length
 * @return string
 */
function generateRandomString($length = 10)
{
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}
/**
 * function to get input from the registration form
 */
function getInput()
    {
        $details = array(
            "email" => "nathalie.brooke@gmail.com",
            "fname" => "Nathalie",
            "lname" => "Brooke",
            "institution" => "RRES",
            "information" => "Some text that will need cleaning",
            "country" => "United Kingdom",
            "consentData" => 1,
            "consentEmail" => 1
        );
        if (isset($_POST['InputFirstName'])) {
            $details['fname'] = cleanQuery($_POST['InputFirstName']);
        }
        if (isset($_POST['RGfname'])) {
            $details['fname'] = cleanQuery($_POST['RGfname']);
        }
        if (isset($_POST['InputLastName'])) {
            $details['lname'] = cleanQuery($_POST['InputLastName']);
        }
        if (isset($_POST['RGlname'])) {
            $details['lname'] = cleanQuery($_POST['RGlname']);
        }
        if (isset($_POST['InputEmail'])) {
            $details['email'] = cleanQuery($_POST['InputEmail']);
        }
        if (isset($_POST['RGposition'])) {
            $details['email'] = cleanQuery($_POST['RGposition']);
        }
        if (isset($_POST['InputInstitute'])) {
            $details['institution'] = cleanQuery($_POST['InputInstitute']);
        }
        if (isset($_POST['RGinstitution'])) {
            $details['institution'] = cleanQuery($_POST['RGinstitution']);
        }
        if (isset($_POST['information'])) {
            $details['information'] = cleanQuery($_POST['information']);
        }
        if (isset($_POST['RGinformation'])) {
            $details['information'] = cleanQuery($_POST['RGinformation']);
        }
        if (isset($_POST['consentCheck'])) {
    
            $details['consentEmail'] = cleanQuery($_POST['consentCheck']);
        }
        if (isset($_POST['RGallowEmails'])) {
            
            $details['consentEmail'] = cleanQuery($_POST['RGallowEmails']);
        }
        if (isset($_POST['understandCheck'])) {
            $details['consentData'] = cleanQuery($_POST['understandCheck']);
        }
    
        if (isset($_POST['inputCountry'])) {
            $details['country'] = cleanQuery($_POST['inputCountry']);
        }
        if (isset($_POST['RGcountry'])) {
            $details['country'] = cleanQuery($_POST['RGcountry']);
        }
        return $details;
    }

/**
 * makeCode()
 * returns timestamp
 *
 * @return mixed
 */
function makeCode()
{
    $time = gettimeofday();
    $code = $time['sec'];
    return $code;
}

/**
 * Sends the preliminary parameters to the db
 *
 * position: the email address
 * vericode: code to match with the cookie to identify machine
 * doorbell: ringing if waiting for registration - and
 * fname:
 * lname:
 * institution: research group, name of institution or N/A
 * information: information about the research
 * allowEmails
 * $vRGFirstName = $row['fname'];
                $vRGLastName = $row['lname'];
                $vRGStudent = $row['fname'];
                $vRGunderstandCheckChecked = "checked";
                $vRGinstitution = $row['intitution'];
                $vRGcountry = $row['country'];
                $vRGSector = $row['role'];
                $vRGStudent = $row['isStudent'];
                $vRGFunding = $row['Funding'];
                $vRGISPG = $row['ISPG'];
                $vRGunderstandCheck2 = $row['agreeCOU'];
                $vRGconsentCheck = $row['allowEmails'];
                $vRGinformation = $row['information'];
                $vRGsupEmail = $row['supervisor_email'];
                $vRGsup_name = $row['supervisor_name'];
                $vRGRothColls = $row['RothColls'];
 *
 * checks that the emails is not already in the database (just in case) if it is not, it preregisters the user. If it is, it does nothing
 *
 * @param Array $answer
 * @return String (the query...)
 *        
 */
function reg2db($answer)
{
    $link = LogMangaAd();
    $consentEmail = 0;
    if (isset($answer['consentEmail'])) {
        if ($answer['consentEmail'] == 'on') {
            $consentEmail = 1;
        }
    }

    $queryCheck = "SELECT * from newmarkers where position LIKE '" . $answer['email'] . "'";

    $results = mysqli_query($link, $queryCheck);

    if (! $results) {
        print("query failed - " . $queryCheck);
    } else {
        $i = 0;
        $nbResults = mysqli_num_rows($results);
        if ($nbResults >= 1) {

            while ($row = mysqli_fetch_array($results)) {
                $queryReturn = "UPDATE newmarkers
                            SET  doorbell='" . $answer['vericode2'] . "'
                            WHERE `position`='" . $answer['email'] . "' and vericode='" . $row['vericode'] . "' ";
            }
        } else {
            
            $queryReturn = "INSERT INTO newmarkers
            (`position`, vericode, doorbell, fname, lname, institution, information, allowEmails, country,  reg_date)
            VALUES('" . $answer['email'] . "', '" . $answer['vericode'] . "', 'Ringing', '" . $answer['fname'] . "', '" . $answer['lname'] . "', '" . $answer['institution'] . "', '" . $answer['information'] . "', " . $consentEmail . ", '" . $answer['country'] . "', NOW());";

            
        }
        $results = mysqli_query($link, $queryReturn);
    }

    return $queryReturn;
}

/*---------------------  Logic ---------------------------*/

if (isset($_SESSION['history'])) {
    $nb = count($_SESSION['history']) - 1;
    if ($nb > 0) {
        $location = "Location: " . $_SESSION['history'][$nb];
    }
}
if (!isset($email)) {$email = 'none Set';}


if (! isset($_COOKIE['email'])) {
    ;
} else {

    // there is a cookie so I check if I want it deleted.
    if (isset($_POST['delete'])&& isset($_POST['UserForms'])) {
        if ($_POST['delete'] == 'delete') {
            $cookie_email_lbl = "email";
            $cookie_email_value = $_POST['email'];
            setcookie('email', "", time() - (86400 * 30), "/");
            setcookie('doorbell', "", time() - (86400 * 30), "/");
            setcookie('time', "", time() - (86400 * 30), "/");
            session_start();
            session_unset();
            session_destroy();
            session_write_close();
            setcookie(session_name(), '', 0, '/');
            session_regenerate_id(true);
            session_start();
            header($location);
        }
    }
}
// we are not loggin in at the moment, so that should not happen! but in case we get back to using magic link, 
if (isset($_COOKIE['email'])) {
    $email = $_COOKIE['email'];
    $doorbell = $_COOKIE['doorbell'];
    $registered = 'yes'; // to see if I need the register button or not
    if ($doorbell == 'ringing') {

        $loggedIn = 'no';
       
        //$strMessage = "<span class=\"badge badge-success mr-1 align-middle \">An email has been sent to " . $email . " <br /> Please check your mail box to confirm your login.</span> ";
    } else if ($doorbell == 'out') {
        //$strMessage = "<span class=\"badge badge-warning mr-1 align-middle\">" . $email . " is not recognised. <br /> Please try again or <a class=\"text-primary\" href=\"".$base."newUser.php\"><u>register</u></a></span>";
        $loggedIn = 'no';
    } else {
        $loggedIn = 'yes';
    }
}

/**
 * this is if we are coming from the login form: we should send the email and wait for the user to click on the login confirmation link
 * 
 * 
 *  there is no cookie, therefore I check that there is a form set and set the cookie if the email is in the database
 *  the cookie will only be here for one hour until it is made more persitent (2 weeks)
*/
if (isset($_POST['email']) || isset($_POST['InputEmail'])) {
    if (isset($_POST['email'])&& isset($_POST['UserForms'])) {

        $email = $_POST['email'];
    } else if (isset($_POST['InputEmail'])) {

        $email = $_POST['InputEmail'];
    }

    if ($email != 'delete' && isset($_POST['UserForms'])) {
        $user = checkUser($email);
        $answers = checkUser($email);

        $answers['vericode2'] = generateRandomString(19);
        $answers['timecode'] = makeCode();
        $answers['email'] = $email;
        $answers['process'] = 'login';

        if ($answers['dbresponse'] == 'yes') {
            setcookie('email', $email, time() + (3600), "/"); // 86400 = 1 day
            setcookie('newemail', $_POST['InputEmail'], time() + (3600), "/"); // 86400 = 1 day
            setcookie('doorbell', 'ringing', time() + (3600), "/"); // 86400 = 1 day
            setcookie('time', $time, time() + (3600), "/"); // 86400 = 1 day

            $emailsent = buildemail($answers);
            $output .= $emailsent;
            $registered = 'yes';
            $strMessage = "<span class=\"badge badge-success\">An email has been sent to " . $email . ".<br /> Please check your mail box (and spam folder) to confirm your login.</span>";
        }
        if ($answers['dbresponse'] == 'no') {
            setcookie('email', $email, time() + (3600), "/"); // 86400 = 1 day
            setcookie('doorbell', 'out', time() + (3600), "/"); // 86400 = 1 day
            $registered = 'no';
            $strMessage = "<span class=\"badge badge-warning  mr-1 align-middle\">" . $email . " is not recognised. <br /> Please try again or  <a class=\"text-primary\" href=\"".$base."newUser.php\"><u>register</u></a></span>";
        }

        $loggedIn = 'no';
        header($location);
    }
}


/**
 * this is if we are from registration form.
 */
if (isset($_POST['process']) && $_POST['process'] == 'process') {
    
    $answers = getInput();
    $email = $answers['email'];
    $answers['vericode'] = generateRandomString(10);
    $answers['vericode2'] = generateRandomString(19);
    $answers['timecode'] = makeCode();
    $answers['process'] = 'register';
     
    $recaptcha_response = $_POST['g-recaptcha-response'];
     
    $recaptcha = file_get_contents($recaptcha_url. '?secret='.$recaptcha_secret.'&response='.$recaptcha_response);
    
   // if ($recaptcha['success']== 1 AND $recaptcha['score'] >= 0.3 AND $recaptcha['action'] = 'register') {
    setcookie('email', $email, time() + (86400 * 30), "/"); // 86400 = 1 day
    setcookie('doorbell', 'ringing', time() + (86400 * 30), "/"); // 86400 = 1 day
    setcookie('time', $time, time() + (86400 * 30), "/"); // 86400 = 1 day
    
    $emailsent = buildemail($answers);
    
    $dbanswer = reg2db($answers);
    
    $output .= '<ul>';
    foreach ($answers as $key => $value) {
        $output .= "<li>" . $key . " : " . $value . "</li>";
    }
    $output .= '</ul>';
    
    $loggedIn = 'no';
    header($location);
    $output .= $emailsent;
    $output .= $dbanswer;

//} else {
//    header($location);
//}
}


/**
 * this is if we are coming from an email Link usually to confirm login or registration.
 */
if (isset($_REQUEST['process']) && $_REQUEST['process'] == 'confirm') {
    // there should be a cookie with the eamil
    $email = $_COOKIE['email'];
    $registeredUser = $email;
    $answer = checkUser($email);
    $output .= '<ul>';
    foreach ($answer as $key => $value) {
        $output .= "<li>" . $key . " : " . $value . "</li>";
    }
    $output .= '</ul>';
    $answer['vericode2'] = $_REQUEST['VC2'];
    $dbanswer = reg2db($answer);

    $loggedIn = 'yes';
    setcookie('doorbell', $_REQUEST['VC2'], time() + (86400 * 30), "/"); // 86400 = 1 day
    setcookie('email', $email, time() + (86400 * 30), "/"); // 86400 = 1 day

    $output .= '<ul>';
    foreach ($answer as $key => $value) {
        $output .= "<li>" . $key . " : " . $value . "</li>";
    }
    $output .= '</ul>';

    $output .= $emailsent;
    $output .= $dbanswer;
    $strMessage = '';
    $nb = count($_SESSION['history']) - 1;
    $location = "Location: " . $_SESSION['history'][$nb];
    header($location);
}



if ($loggedIn == 'yes' && $email != 'delete') {

    $strRegister = formIN($email);

    //$registeredUser = $_COOKIE['email'];
    $registeredUser = "";
    $_SESSION["email"] = $email;
} else {

    $strRegister = $formOUT;

    $registeredUser = "Get Link";
}