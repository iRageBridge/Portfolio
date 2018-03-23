<?php
//PHP code

$cookie_value;
$cookie_name = "cookie";

//Cheking to see if the xml file with the value of the stored timestamp exists, and then assinging that timestamp to the cookie.
if(file_exists("xml/user".$_COOKIE['cookie'].".xml")){
    $cookie_value = $_COOKIE['cookie'];
}

//Checking to see if the cookie is set, if it isnt, set it.
if(!isset($_COOKIE[$cookie_name])){
    $timeStamp = time();
    setcookie($cookie_name, $timeStamp, time()+(86400*30));
}

//Making the xml file if the cookie value is blank.
if($cookie_value != "") {
    $xmlfile = "xml\user" . $cookie_value . ".xml";
    $currentXML = simplexml_load_file($xmlfile);
}
?>

<!--HTML Code-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CV Upload</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <script src="https://npmcdn.com/tether@1.2.4/dist/js/tether.js"></script>
    <script src="JS/jquery-3.2.1.min.js"></script>
    <script src="JS/bootstrap.min.js"></script>
</head>
<body>
<form action="action.php" name="image" method="POST" enctype="multipart/form-data" id="form">
    <h1>CV Builder</h1>
    <hr/>
    <ul class="form-style-1">
        <li>
            <label>Full Name <span class="required">*</span></label><div id="nameError" style="color: #ff0000;">Please enter a name.</div>
            <input type="text" class="field-long" id="name" name="name" value="<?php echo $currentXML->name[0];?>">
        </li>
        <li>
            <label>Picture<span class="required">*</span></label><div id="pictureError" style="color: #ff0000;">Please select a picture.</div>
            <input type="file" name="picture" id="picture">
        </li>
        <li>
            <label>Address<span class="required">*</span></label><div id="addressError" style="color: #ff0000;">Please enter an address.</div>
            <textarea name="address" id="address" class="field-long field-textarea"><?php echo $currentXML->address[0];?></textarea>
        </li>
        <li>
            <label>Date of Birth<span class="required">*</span></label><div id="dobError" style="color: #ff0000;">Please enter a Date of Birth.</div>
            <input type="date" name="dob" id="dob" value="<?php echo $currentXML->dob[0];?>"><br/><br/>
        </li>
        <li>
            <label>Experience<span class="required">*</span></label><div id="expError" style="color: #ff0000;">Please enter your experience.</div>
            <textarea name="exp" id="exp" class="field-long field-textarea"><?php echo $currentXML->exp[0];?></textarea><br/><br/>
        </li>
        <li>
            <label>Education<span class="required">*</span></label><div id="eduError" style="color: #ff0000;">Please enter your education.</div>
            <textarea name="edu" id="edu" class="field-long field-textarea"><?php echo $currentXML->edu[0];?></textarea><br/><br/>
        </li>
        <li>
            <section id="onlineRadio">View Online
                <input type="radio" id="viewOnline" name="option" checked="checked" value="viewOnline">
            </section>
            <section id="downloadRadio">Download (PDF)
                <input type="radio" id="download" name="option" value="download">
            </section><br/><br/>
        </li>
        <li>
            <input type="submit" name="submit" id="submit" value="Upload">
        </li>
    </ul>
</form>
<hr/>
<script>
    //jQuery Code
    $(document).ready(function() {
        //Hiding the error messages on load
        hideErrors();
        $('form').submit(function(){
            hideErrors();//Had to call twice as it was glitching when the page loaded, this seemed to fix it
            //removing whitespace from the input field data and setting to jquery variable
            var name=$.trim($('#name').val());
            var picture=$.trim($('#picture').val());
            var address=$.trim($('#address').val());
            var dob=$.trim($('#dob').val());
            var exp=$.trim($('#exp').val());
            var edu=$.trim($('#edu').val());

            //Checking to see if any of the input fields are blank, if they are scroll to top and show error message
            if(name === '') {
                scrollError();
                $("#nameError").show();
            }
            if(picture === ''){
                scrollError();
                $("#pictureError").show();
            }
            if(address === ''){
                scrollError();
                $("#addressError").show();
            }
            if(dob === ''){
                scrollError();
                $("#dobError").show();
            }
            if(exp === ''){
                 $("#expError").show();
            }
            if(edu === ''){
                $("#eduError").show();
            }

            //Returning false after checking the errors, this made it so multiplle errors can be displayed
            if(name ==='' || picture ==='' || address ==='' || dob ==='' || exp ==='' || edu ===''){
                return false;
            }

            //function to scroll to top of page
            function scrollError(){
                $("html,body").animate({
                    scrollTop: 0
                },"fast");
            }
        });

        //Function to hide error messages
        function hideErrors(){
            $("#nameError").hide();
            $("#pictureError").hide();
            $("#addressError").hide();
            $("#dobError").hide();
            $("#expError").hide();
            $("#eduError").hide();
        }
    });
</script>
</body>
</html>
