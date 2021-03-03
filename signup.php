    <?php
     ob_start();
     session_start();
     if( isset($_SESSION['user'])!="" ){
      header("Location: home.php");
     }
     include_once 'dbconnect.php';

     $error = false;

     if ( isset($_POST['btn-signup']) ) {

      $name = trim($_POST['name']);
      $name = strip_tags($name);
      $name = htmlspecialchars($name);
         
      $surname = trim($_POST['surname']);
      $surname = strip_tags($surname);
      $surname = htmlspecialchars($surname);
         
      $adress = trim($_POST['adress']);
      $adress = strip_tags($adress);
      $adress = htmlspecialchars($adress);

      $email = trim($_POST['email']);
      $email = strip_tags($email);
      $email = htmlspecialchars($email);

      $pass = trim($_POST['pass']);
      $pass = strip_tags($pass);
      $pass = htmlspecialchars($pass);

      if (empty($name)) {
       $error = true;
       $nameError = "Please enter your  name.";
      } else if (strlen($name) < 3) {
       $error = true;
       $nameError = "Name must have atleat 3 characters.";
      } else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
       $error = true;
       $nameError = "Name must contain alphabets";
      }
         
         if (empty($surname)) {
       $error = true;
       $surnameError = "Please enter your surname.";
      } else if (strlen($surname) < 3) {
       $error = true;
       $surnameError = "Surname must have atleat 3 characters.";
      } else if (!preg_match("/^[a-zA-Z ]+$/",$surname)) {
       $error = true;
       $surnameError = "Surname must contain alphabets";
      }
         
          if (empty($adress)) {
       $error = true;
       $adressError = "Please enter your adress.";
      } else if (strlen($adress) < 3) {
       $error = true;
       $adressError = "Adress must have atleat 3 characters.";
      }         
         
         

      if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
       $error = true;
       $emailError = "Please enter valid email address.";
      } else {
       $query = "SELECT user_mail FROM users WHERE user_mail='$email'";
       $result = mysqli_query($conn,$query);
       $count = mysqli_num_rows($result);
       if($count!=0){
        $error = true;
        $emailError = "Provided Email is already in use.";
       }
      }
      if (empty($pass)){
       $error = true;
       $passError = "Please enter password.";
      } else if(strlen($pass) < 6) {
       $error = true;
       $passError = "Password must have atleast 6 characters.";
      }

      
        $user_type = 0;

      if( !$error ) {

       $query = "INSERT INTO users(user_name,user_surname,user_adress,user_mail,user_password,user_type) VALUES('$name','$surname','$adress','$email','$pass','$user_type')";
       $res = mysqli_query($conn,$query);

       if ($res) {
        $errTyp = "success";
        $errMSG = "Successfully registered, you may login now";
        unset($name);
        unset($surname);
        unset($adress);
        unset($email);
        unset($pass);
       } else {
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
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <title>Sign Up</title>
    </head>   
    <body content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg.jpg');">
			<div class="wrap-login100 p-l-110 p-r-110 p-t-62 p-b-33">
                <form class="login100-form validate-form flex-sb flex-w" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">           
             <div class="login100-form-title p-b-53">
                Sign Up.
                </div>          
    
            
    <?php
            
       if (isset($errMSG) ) {
        ?>
        <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>      
          <?php } ?>

 <div class="p-t-31 p-b-9">
    <span class="txt1">Name:</span>
</div> 		
    <div class="wrap-input100 validate-input"> 					    
    <input type="text" name="name" class="input100" placeholder="Enter Name" maxlength="30" value="<?php echo $name ?>" />
     <span class="focus-input100"></span>
    </div>      
    <span style="color:red" class="text-danger"><?php echo $nameError; ?></span>
     
     
    <div class="p-t-31 p-b-9">
    <span class="txt1">Surname:</span>
</div> 		
    <div class="wrap-input100 validate-input"> 	            
    <input type="text" name="surname" class="input100" placeholder="Enter Your Surname" maxlength="30" value="<?php echo $surname ?>" />
     <span class="focus-input100"></span>
    </div>
    <span style="color:red" class="text-danger"><?php echo $surnameError; ?></span>
       
       
        
    <div class="p-t-31 p-b-9">
    <span class="txt1">Adress:</span>
    </div> 		
    <div class="wrap-input100 validate-input"> 	 
    <input type="text" name="adress" class="input100" placeholder="Enter Your Adress" maxlength="75" value="<?php echo $adress ?>" />
     <span class="focus-input100"></span>
     </div>
    <span style="color:red" class="text-danger"><?php echo $adressError; ?></span>   
        
  <div class="p-t-31 p-b-9">
    <span class="txt1">E-mail:</span>
    </div> 		
    <div class="wrap-input100 validate-input"> 	
   <input type="email" name="email" class="input100" placeholder="Enter Your Email" maxlength="75" value="<?php echo $email ?>" />
      <span class="focus-input100"></span>
   </div>
    <span style="color:red" class="text-danger"><?php echo $emailError; ?></span>
              
                      
    <div class="p-t-31 p-b-9">
    <span class="txt1">Password:</span>
    </div> 		
    <div class="wrap-input100 validate-input"> 	        
     <input type="password" name="pass" class="input100" placeholder="Enter Password" maxlength="11" />
         <span class="focus-input100"></span>
       </div>
        <span style="color:red" class="text-danger"><?php echo $passError; ?></span>

        <div class="container-login100-form-btn m-t-17">             
             <button type="submit" class="login100-form-btn" name="btn-signup">Sign Up</button>
             </div>
            
           <div class="w-full text-center p-t-55">
             <a href="login.php">Already have an account? Sign in !</a>  </div>
             </form>         
      </div>                 
    </div>
         </div>
    </body>
    </html>
    <?php ob_end_flush(); ?>