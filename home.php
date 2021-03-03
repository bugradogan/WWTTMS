<?php
 ob_start();
 session_start();
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;

 require 'vendor/phpmailer/phpmailer/src/Exception.php';
 require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
 require 'vendor/phpmailer/phpmailer/src/SMTP.php';




 require_once 'dbconnect.php';
 
 if( !isset($_SESSION['user']) ) {
  header("Location: login.php");
  exit;
 }

 $res=mysqli_query($conn,"SELECT * FROM users WHERE user_id=".$_SESSION['user']);
 $userRow=mysqli_fetch_array($res);
 $db_name =  $userRow['user_name'];
 $db_surname =  $userRow['user_surname'];
 $db_adress =  $userRow['user_adress'];
 $db_mail =  $userRow['user_mail'];
 $db_user_id =  $userRow['user_id'];


if ( isset($_POST['btn-ticket']) ) {

         
      $troubledescp = trim($_POST['troubledescp']);
      $troubledescp = strip_tags($troubledescp);
      $troubledescp = htmlspecialchars($troubledescp);
    
    
    if (empty($troubledescp)) {
       $error = true;
       $troubleError = "Please enter your  trouble description.";
      } else if (strlen($troubledescp) < 3) {
       $error = true;
       $troubleError = "Your  trouble description must have atleat 3 characters.";
      } 
     
       $query3 = "SELECT isFixed FROM trouble WHERE user_id='$db_user_id' ORDER BY trouble_id DESC LIMIT 1";      
       $result2 = mysqli_query($conn,$query3);
       $count = mysqli_fetch_array($result2);
       if($count > 0 && $count['isFixed'] ==0){
        $error = true;
        $troubleError = "You already have a fault record. Please wait until the problem is fixed.";
       }
      
      
         
        
    $isFixed = 0;
    if( !$error ) {

       $query2 = "INSERT INTO trouble(trouble_inf,trouble_adress,isFixed,user_id,crew_id) 
       VALUES('$troubledescp','$db_adress','$isFixed','$db_user_id','0')";
       $res2 = mysqli_query($conn,$query2);

       if ($res2) {        
        unset($troubledescp);
        unset($isFixed);  
        $res3=mysqli_query($conn,"SELECT * FROM trouble WHERE user_id='$db_user_id' ORDER BY trouble_id DESC LIMIT 1");
        $userRow2=mysqli_fetch_array($res3);
        $trouble_id =  $userRow2['trouble_id'];
        $trouble_descp =  $userRow2['trouble_inf'];
        $message = "Dear $db_name $db_surname. Your ticket information has been saved. Your Ticket Number is: $trouble_id";
           
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
        $mailer->Subject = 'Your Ticket Information';
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
<p style='font-size:20px'>Your ticket information has been saved. Table of Ticket Information </p><br>
 <table>
  <tr>
    <th>Ticket Number</th>
    <th>Adress</th>
    <th>Ticket Information</th>
  </tr>
  <tr>
    <td>$trouble_id</td>
    <td>$db_adress</td>
    <td>$trouble_descp</td>
  </tr>
  

</table>

</body>
</html>

        ";            
        if($mailer->send())
             {
                $errTyp = "success";
                $errMSG = "Successfully sended.Your information was sent by email. You may logout now.";
             }
                else
                    "failed"; 
                 
       } 
        else 
       {
        $errTyp = "danger";
        $errMSG = "Something is wrong..."; 
       } 
      }
     } 
 

?>


<!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Trouble Ticket System</title>
    <link rel="stylesheet" href="css/main.css">    
    <link rel="stylesheet" type="text/css" href="css/util.css">
    </head>
    <body>
           
    <div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg.jpg');">
			<div class="wrap-login100 p-l-110 p-r-110 p-t-62 p-b-33">
    <form class="login100-form validate-form flex-sb flex-w" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">         

         <div class="login100-form-title p-b-53">
                 Welcome Trouble Ticket System
        </div>    
                
       
      <?php if ( isset($errMSG) ) { ?>       
        <div class="form-group">
                 <div class="alert alert-danger">
                    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
        </div> 
        <?php }?>       
     <div class="p-t-31 p-b-9">
        <span class="txt1">
           Name:
        </span>
    </div>
    <div class="wrap-input100 validate-input">
    <input type="text" name="name" class="input100" placeholder="Enter Name" maxlength="50" disabled value="<?php echo $db_name ?>" /></div>
    <span style="color:red" class="text-danger"><?php echo $nameError; ?></span>
              
      <div class="p-t-31 p-b-9">
        <span class="txt1">
           Surname:
        </span>
    </div>        
               
     <div class="wrap-input100 validate-input">           
    <input type="text" name="surname" class="input100" placeholder="Enter Your Surname" maxlength="50" disabled value="<?php echo $db_surname ?>" /></div>
    <span style="color:red" class="text-danger"><?php echo $surnameError; ?></span>       
       <div class="p-t-31 p-b-9">
        <span class="txt1">
           Adress:
        </span>
    </div>   
    <div class="wrap-input100 validate-input">   
    <input type="text" name="adress" class="input100" placeholder="Enter Your Adress" maxlength="50" disabled value="<?php echo $db_adress ?>" /></div>
    <span style="color:red" class="text-danger"><?php echo $adressError; ?></span>
            
     <div class="p-t-31 p-b-9">
        <span class="txt1">
           Trouble Description:
        </span>
    </div>           
            
     <div class="wrap-input100 validate-input">           
    <label for="troubledescp"></label>
    <textarea class="input100" id="troubledescp" name="troubledescp"   placeholder="Enter Your Trouble Description"> </textarea></div><br>
    <span style="color:red" class="text-danger input100"><?php echo $troubleError; ?></span><br>     
        
    <div class="container-login100-form-btn m-t-17">
        <button type="submit" class="login100-form-btn" name="btn-ticket">Send Trouble</button></div>  
        
        
        
     <div class="w-full text-center p-b-53">					
        <a href="logout.php?logout" class="txt2">Logout</a></div>  
	
             </form> 
                    </div>
                         </div>
                         </div>
                         
    </body>
    </html>
    <?php ob_end_flush(); ?>
    
