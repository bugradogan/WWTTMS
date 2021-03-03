<?php
     ob_start();
     session_start();
     include_once 'dbconnect.php';
    $user_type =  $_SESSION['user_type'];
     if ( isset($_SESSION['user'])!="" && $user_type == 0)
     {
      header("Location: home.php");
      exit;    
     }
    else if ( isset($_SESSION['user'])!="" && $user_type == 1) 
    {
      header("Location: master.php");
      exit;
    } 
    else if ( isset($_SESSION['user'])!="" && $user_type == 2) 
    {
      header("Location: crew.php");
      exit;
    } 
     $error = false;
     
     if( isset($_POST['btn-login']) ) { 
      
      $email = trim($_POST['email']);
      $email = strip_tags($email);
      $email = htmlspecialchars($email);
      
      $pass = trim($_POST['pass']);
      $pass = strip_tags($pass);
      $pass = htmlspecialchars($pass);
      
      if(empty($email)){
       $error = true;
       $emailError = "Please enter your email address.";
      } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
       $error = true;
       $emailError = "Please enter a valid email address.";
      }
      
      if(empty($pass)){
       $error = true;
       $passError = "Please enter your password.";
      }
      
      if (!$error) {          
    
       $res=mysqli_query($conn,"SELECT user_id, user_name, user_password,user_type FROM users WHERE user_mail='$email'");
       $row=mysqli_fetch_array($res);
       $count = mysqli_num_rows($res);
      
       
       if( $count == 1 && $row['user_password']==$pass && $row['user_type'] ==0 ) {
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['user'] = $row['user_id'];
        header("Location: home.php");
       } 
        else if($count == 1 && $row['user_password']==$pass &&  $row['user_type'] == 1 )
        {
            $_SESSION['user'] = $row['user_id'];
            $_SESSION['user_type'] = $row['user_type'];
            header("Location: master.php");
        } 
           else if($count == 1 && $row['user_password']==$pass &&  $row['user_type'] == 2 )
        {           
            $_SESSION['user'] = $row['user_id'];
            $_SESSION['user_type'] = $row['user_type'];
            header("Location: crew.php");
        } 
          else {
        $errMSG = "Incorrect Credentials, Please try again...";
       }
        
      }
      
     }
    ?>
 <!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/util.css">
    </head>
    <body>

    <div class="limiter">
		<div class="container-login100" style="background-image: url('images/bg.jpg');">
		<div class="login100-form-title p-b-53">
                 WELCOME WWTTMS
                </div>
			<div class="wrap-login100 p-l-110 p-r-110 p-t-62 p-b-33">
       
        <form class="login100-form validate-form flex-sb flex-w" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">        
                 
             <div class="login100-form-title p-b-53">
                 Sign In.
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
							E-mail
						</span>
					</div>
               
                 <div class="wrap-input100 validate-input">                
                 <input  type="email" name="email" class="input100" placeholder="Your Email" value="<?php echo $email; ?>" maxlength="75" />
                 <span class="focus-input100"></span>
                  </div>                
                   <span style="color:red" class="text-danger"><?php echo $emailError; ?></span> 
                                    

                <div class="p-t-13 p-b-9">
						<span class="txt1">Password</span>
                </div> 
                 <div class="wrap-input100 validate-input">                 
                 <input type="password" name="pass" class="input100" placeholder="Your Password" maxlength="11" />
                   <span class="focus-input100"></span>
                    </div>
                    <span style="color:red" class="text-danger"><?php echo $passError; ?></span>
                
             <br>          
                <div class="container-login100-form-btn m-t-17">
                 <button  type="submit" class="login100-form-btn" name="btn-login">Sign In</button>
                </div>
                     
          <div class="w-full text-center p-t-55">
						<span class="txt2">
							Not a member?
						</span>

						<a href="signup.php" class="txt2 bo1">
							Sign up now
						</a>
					</div>         
                 </form>
            </div>     
        </div> 
    </div>

    </body>
    </html>
    <?php ob_end_flush(); ?>