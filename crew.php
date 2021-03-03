<?php
ob_start();
session_start();
include_once 'dbconnect.php';

$user_type =  $_SESSION['user_type'];
 
 if( !isset($_SESSION['user']) || $_SESSION['user_type'] !=2 ) {
  header("Location: login.php");
  exit;
 }


$user_id = $_SESSION['user'];
$crew=mysqli_query($conn,"SELECT crew_id FROM crew WHERE user_id='$user_id'");
$row2=mysqli_fetch_array($crew); 
$crew_id=$row2['crew_id'];


 $res=mysqli_query($conn,"SELECT * FROM trouble WHERE crew_id='$crew_id'");
 $count = mysqli_num_rows($res);

    
    
    
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
           <h2 class="text-center">Trouble Ticket Repair Screen</h2><br> 
            <table class='table table-bordered table-striped table-dark'>                  
             <?php      
if($count > 0)
{
    echo "  
        <tr>
            <td>TT Number</td>
            <td>Trouble Information</td>
            <td>Adress</td>
            <td>Fixed</td>
            <td>User ID</td>            
            <td>Repair</td>
        </tr>";
    while($row=mysqli_fetch_array($res))
    {
        if($row['isFixed'] == 0){
            $isFixedMsg = "No";
            if($row['crew_id'] == 0){
            $currentCrew = "Unassigned";}
            else if($row['crew_id'] == 1){
            $currentCrew = "A";}
             else if($row['crew_id'] == 2){
            $currentCrew = "B";}
             else if($row['crew_id'] == 3){
            $currentCrew = "C";}
            
            
        echo "        
         <tr>        
            <td>{$row['trouble_id']}</td>
            <td>{$row['trouble_inf']}</td>
            <td>{$row['trouble_adress']}</td>
            <td>{$isFixedMsg}</td>
            <td>{$row['user_id']}</td>            
            <td><a class='login100-form-btn text-center' href = 'repair.php?id={$row['trouble_id']}'>Repair</a></td>
        </tr><br>                            
         
               
        ";}
    }
 }
else
     echo " <p class='text-center' style='color:white'>There is no assignment for your crew.</p>";
 
  ?>    
 
     </table>   
</div>
  </div> 
     <div class="w-full text-center p-b-53">					
        <a href="logout.php?logout" class="txt2">Logout</a></div>      
      </div>     
     
  </body>
</html>