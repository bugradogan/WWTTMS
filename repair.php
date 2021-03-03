<?php
ob_start();
session_start();

use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;

 require 'vendor/phpmailer/phpmailer/src/Exception.php';
 require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
 require 'vendor/phpmailer/phpmailer/src/SMTP.php';
include_once 'dbconnect.php';
$user_type =  $_SESSION['user_type'];
 
 if( !isset($_SESSION['user']) || $_SESSION['user_type'] !=2 ) {
  header("Location: login.php");
  exit;
 }

$id = $_GET["id"];
$res=mysqli_query($conn,"SELECT * FROM trouble WHERE trouble_id='$id'");
$row= mysqli_fetch_array($res);
$trouble_id = $row['trouble_id'];
$trouble_descp =  $row['trouble_inf'];
$trouble_adress =  $row['trouble_adress'];
$user_id = $row['user_id'];
$res2=mysqli_query($conn,"SELECT user_name,user_surname,user_mail FROM users WHERE user_id='$user_id'");
$row2= mysqli_fetch_array($res2);
$db_name = $row2['user_name']; 
$db_surname = $row2['user_surname']; 
$db_mail = $row2['user_mail'];

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">    
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" href="css/style.css">

    <title>Trouble List</title>
  </head>
    <style>body{
        background-image: url('images/bg.jpg');     
        background-size: cover;
}</style>
  <body>  
  
  
 <div class="container" >       
   <div class="row justify-content-center">
       <div class="col">   
           <h2 class="text-center">Crew Assigment Screen</h2><br>           
<?php 
   
       if($_POST)
       {
            $repair = $_POST['repair'];
           $update=mysqli_query($conn,"UPDATE trouble SET isFixed='$repair' WHERE trouble_id='$id'");
          if($update)
          { 
             // Mail Settings    
        $mailer = new PHPMailer();
        $mailer-> isSMTP();
        $mailer->SMTPKeepAlive = true;
        $mailer->SMTPAuth = true;
        $mailer->SMTPSecure = 'tls';
           
        $mailer->Port = 587;
        $mailer->Host = 'smtp.gmail.com';
        
        $mailer->Username = 'noreply.wwttms@gmail.com';
        $mailer->Password = 'Bugra.dogan94';
           
        $mailer->SetFrom('noreply.wwttms@gmail.com','WWTTMS');
        $mailer->addAddress("$db_mail");
           
        $mailer->isHTML(true);        
        $mailer->Subject = 'Trouble Fixed';
              
        $mailer->Body = "       
        
         <!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
</head>
<body>

<h2>Dear Mr./Mrs. $db_name $db_surname</h2>
<p style='font-size:20px'>Your problem in the table has been repaired.</p><br>
 <table>
  <tr>
    <th>Ticket Number</th>
    <th>Adress</th>
    <th>Ticket Information</th>    
  </tr>
  <tr>
    <td>$trouble_id</td>
    <td>$trouble_adress</td>
    <td>$trouble_descp</td>
    
  </tr>
  

</table>

</body>
</html>
        

        ";            
            if($mailer->send())
             {                
                echo " <p class='text-center' style='color:lime'>Successfully repaired and e-mail was sent to the user.. You are directed to the homepage.</p>";
                header("Refresh:2; url=crew.php");
             }
                else
                    "failed"; 
 
          }           
           else
               echo " <p class='text-center' style='color:red'>Error...</p>";
           
       }
   else
   {
        if($row['isFixed'] == 0){
            $isFixedMsg = "No";      
            
      
        echo "  
        <table class='table table-bordered table-striped table-dark'>     
        <tr>
            <td>TT Number</td>
            <td>Trouble Information</td>
            <td>Adress</td>
            <td>Fixed</td>
            <td>User ID</td>            
            <td>Repair Option</td>
            <td>Repair</td>
        </tr>      
        <form action='' method = 'POST'
         <tr>        
            <td>{$row['trouble_id']}</td>
            <td class='fs-15'>{$row['trouble_inf']}</td>
            <td class='fs-15'>{$row['trouble_adress']}</td>
            <td>{$isFixedMsg}</td>
            <td>{$row['user_id']}</td>            
            <td>             
            <select class ='browser-default custom-select' name ='repair'>
                    <option  value='' disabled selected>Select Option</option>                    
                    <option value='1'>Repair</option>                    
            </select></td> 
             <td><input  type ='submit' value='UPDATE' /></td>
        </tr><br>   </form> </table>                         
         
               
        ";}
   }
            
    
  ?>    
 
       
</div>
  </div>      
      </div>
  </body>
</html>