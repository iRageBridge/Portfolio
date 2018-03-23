<?php

session_start();

include ('mpdf/mpdf.php');

//Setting value of the cookie, and the upload directory of the image upload.
$cookie = $_COOKIE['cookie'];
$uploaddir = 'C:\wamp64\www\SSP2\A1\Simon_Standbridge\uploads';
$uploadfile = $uploaddir."\\".$cookie.basename($_FILES['picture']['name']);

if(move_uploaded_file($_FILES['picture']['tmp_name'],"$uploadfile")){
    echo "File Downloaded to Downloads directory.<br/><form action='index.php'><input type='submit' value='Go Back'></form>";
}

//Use this variable to display the uploaded image on a pdf page
$temp_pdf = "<img src='".$uploadfile."'/>";

//Session code, could have done without but needed to show I could use sessions.
$_SESSION['name'] = $_POST['name'];
$_SESSION['image'] = $temp_pdf;
$_SESSION['address'] = $_POST['address'];
$_SESSION['dob'] = $_POST['dob'];
$_SESSION['exp'] = $_POST['exp'];
$_SESSION['edu'] = $_POST['edu'];

//import stylesheet for MPDF
$stylesheet = file_get_contents('css\styles.css');

//function to sanitise the input data, remove tags etc.
function clean_input($input){
    $input = strip_tags($input);
    $input = htmlentities($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);

    return $input;
}

//Making the new pdf file, and writing the required html to it and styling it.
$mpdf = new mPDF();
$mpdf->WriteHTML($stylesheet,1);
$mpdf->WriteHTML("<h1>".$_POST['name']." Curriculum Vitae</h1><br/><div id='left'><div class='cvFields'>".$temp_pdf."</div></div>"."<div id='right'><div class='addressField'>Address: ".clean_input($_SESSION['address'])."</div>"."<div class='dob'>Date of Birth: ".clean_input($_SESSION['dob'])."</div></div><br/>"."<div class='cvFields'><h2>Experience</h2>".clean_input($_SESSION['exp'])."</div><br/>"."<div class='cvFields'><h2>Education</h2><br/>".clean_input($_SESSION['edu'])."<br/>");

//Checking to see if the user selected view or download, and proceeding accordingly
if($_POST['option'] === "viewOnline") {
    $mpdf->Output();
}
else if($_POST['option'] === "download") {
    $mpdf->Output('downloads\cv.pdf', 'F');
}

//Code to create the xml file and various elements in it.
$xml = new DOMDocument();
$xml_document = $xml->createElement("document");
$xml_name = $xml->createElement("name");
$xml_address = $xml->createElement("address");
$xml_picture = $xml->createElement("picture");
$xml_dob = $xml->createElement("dob");
$xml_exp = $xml->createElement("exp");
$xml_edu = $xml->createElement("edu");

$xml->appendChild($xml_document);
$xml_document->appendChild( $xml_name );
$xml_document->appendChild( $xml_address );
$xml_document->appendChild( $xml_picture );
$xml_document->appendChild( $xml_dob );
$xml_document->appendChild( $xml_exp );
$xml_document->appendChild( $xml_edu );

$xml_name -> nodeValue = $_POST['name'];
$xml_address -> nodeValue = $_POST['address'];
$xml_picture -> nodeValue = $temp_pdf;
$xml_dob -> nodeValue = $_POST['dob'];
$xml_exp -> nodeValue = $_POST['exp'];
$xml_edu -> nodeValue = $_POST['edu'];

$xml->formatOutput = true;
$xml->save('C:\wamp64\www\SSP2\A1\Simon_Standbridge\xml\user'.$_COOKIE['cookie'].'.xml');