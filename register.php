
<!-- Registration -->
<?php
//if(!empty($_POST['submit'])){
    $name = $_POST['name'];
    $fhname = $_POST['fhname'];
    $raddress = $_POST['raddress'];
    $ofaddress = $_POST['ofaddress'];
    $pin = $_POST['pin'];
    $tphone = $_POST['tphone'];
    $mobile = $_POST['mobile'];
    $visitor_email = $_POST['email'];
    $membership = $_POST['membership'];
    $bcmember = $_POST['bcmember'];
    $date = $_POST['date'];
    $upload = date('Y_m_d_H_i_s_').basename($_FILES["upload"]["name"]);//$_POST['upload'];

   $target_dir = "upload/";
$target_file = $target_dir . $upload;
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["upload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["upload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["upload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
date_default_timezone_set('Asia/Calcutta');
//<img src="upload/"'.$upload.'">
$data='
<form action="register.php" method="POST">
<table>
<tr>
<td>Date : '.date('Y-m-d H:i:s').'</td>
<td><img src="upload/'.$upload.'" width="100px" height="100px"></td>
</tr>
<tr>
<td>Name:</td>
<td>'.$name.'</td>
</tr>
<tr>
<tr>
<td>Father’s/Husband’s Name:</td>
<td>'.$fhname.'</td>
</tr>
<tr>
<tr>
<td>Residential Address:</td>
<td>'.$raddress.'</td>
</tr>
<tr>
<tr>
<td>Office Address:</td>
<td>'.$ofaddress.'</td>
</tr>
<tr>
<tr>
<td>Pin code:</td>
<td>'.$pin.'</td>
</tr>
<tr>
<tr>
<td>Telephone No:</td>
<td>'.$tphone.'</td>
</tr>
<tr>
<td>Mobile No:</td>
<td>'.$mobile.'</td>
</tr>
<tr>
<td>Email ID:</td>
<td>'.$visitor_email.'</td>
</tr>
<tr>
<td>Membership in which State Bar Council?:</td>
<td>'.$membership.'</td>
</tr>
<tr>
<td>Bar Council Enrollment Number:</td>
<td>'.$bcmember.'</td>
</tr>
<tr>
<td>Year of enrollment in Bar Council:</td>
<td>'.$date.'</td>
</tr>
</table>
</form>
';

//pdf start


    //collect form data
 

  /*require("fpdf/fpdf.php");
  $pdf=new FPDF();
  $pdf->Addpage();
  $pdf->SetFont("Arial", "B", 16);
  $pdf->Cell(0,10,"Welcome {$name}",1,0,C);
  $pdf->output();*/

include("mpdf/mpdf.php");
$mpdf=new mPDF();
$mpdf->WriteHTML("New Associate Details");
$mpdf->WriteHTML($data);
//$mpdf->Cell(0,10,"Welcome {$name}",1,0,C);
//$mpdf->Output();

$mpdf->Output("upload/".$mobile.".pdf",'F');
//$mail="upload/".$mobile.".pdf";

$files="upload/".$mobile.".pdf";

  ///attachement start
  
	// send


$email_from = 'info@prlawassociates.com';
$email_subject = "New Associate Details";
$htmlbody = "Associate Details\n Name : $name\n Father’s/Husband’s Name: $fhname\n Residential Address : $raddress\n Office Address : $ofaddress\n Pin code : $pin\n Telephone No : $tphone\n Mobile No : $mobile\n Email ID : $visitor_email\n Membership in which State Bar Council? : $membership\n Bar Council Enrollment Number : $bcmember\n Year of enrollment in Bar Council : $date";



$to = $visitor_email; //Recipient Email Address

$subject = "New Associate Form"; //Email Subject

$headers = "From: ".$email_from."\r\nReply-To: " .$email_from. "\r\nCc: info@prlawassociates.com";

$random_hash = md5(date('r', time()));

$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\"";

$attachment = chunk_split(base64_encode(file_get_contents($files))); // Set your file path here

//define the body of the message.

$message = "--PHP-mixed-$random_hash\r\n"."Content-Type: multipart/alternative; boundary=\"PHP-alt-$random_hash\"\r\n\r\n";
$message .= "--PHP-alt-$random_hash\r\n"."Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"."Content-Transfer-Encoding: 7bit\r\n\r\n";

//Insert the html message.
$message .= $htmlbody;
$message .="\r\n\r\n--PHP-alt-$random_hash--\r\n\r\n";

//include attachment
$message .= "--PHP-mixed-$random_hash\r\n"."Content-Type: application/zip; name=\"$files\"\r\n"."Content-Transfer-Encoding: base64\r\n"."Content-Disposition: attachment\r\n\r\n";
$message .= $attachment;
$message .= "/r/n--PHP-mixed-$random_hash--";

//send the email
$mail = mail( $to, $subject , $message, $headers );

echo $mail ? "Mail sent" : "Mail failed";
header("Location: success.php?id=".$mobile.".pdf");
/////////////enndddd
exit;