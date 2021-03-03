<?php
ob_start();
session_start();
include_once 'dbconnect.php';

$user_type =  $_SESSION['user_type'];
 
 if( !isset($_SESSION['user']) || $_SESSION['user_type'] !=1 ) {
  header("Location: login.php");
  exit;
 }

$id = $_GET["id"];

$res=mysqli_query($conn,"SELECT * FROM trouble WHERE trouble_id='$id'");
$row= mysqli_fetch_array($res);



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
           <table class="table table-bordered table-striped table-dark">     
        <tr>
            <td>TT Number</td>
            <td>Trouble Information</td>
            <td>Adress</td>
            <td>Fixed</td>
            <td>User ID</td>
            <td>Current Crew</td>
            <td>Crew ID</td>
            <td>Crew Assign</td>
        </tr>      
             <?php 
   
       if($_POST)
       {
          $crew = $_POST['crew'];
           $update=mysqli_query($conn,"UPDATE trouble SET crew_id='$crew' WHERE trouble_id='$id'");
          if($update)
          {          
            echo " <p class='text-center' style='color:lime'>Successfully updated. You are directed to the homepage.</p>";
           header("Refresh:2; url=master.php");
          }
           
           else
               echo " <p class='text-center' style='color:red'>Error...</p>";
           
       }
   else
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
        <form action='' method = 'POST'
         <tr>        
            <td>{$row['trouble_id']}</td>
            <td class='fs-15'>{$row['trouble_inf']}</td>
            <td>{$row['trouble_adress']}</td>
            <td>{$isFixedMsg}</td>
            <td>{$row['user_id']}</td>
            <td>{$currentCrew}</td>
            <td> 
            
            <select class ='browser-default custom-select' name ='crew'>
                    <option  value='' disabled selected>Select Crew</option>
                    <option value='1'>A</option>
                    <option value='2'>B</option>
                    <option value='3'>C</option>
                </select>            
            </td> 
             <td><input  type ='submit' value='UPDATE' /></td>
        </tr><br>       </form>                          
         
               
        ";}
   }
            
    
  ?>    
 
     </table>   
</div>
  </div>      
      </div>
  </body>
</html>