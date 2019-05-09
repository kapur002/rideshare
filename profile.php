<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}
include('connection.php');

$user_id = $_SESSION['user_id'];

//get username and email
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);
//echo '<pre>' .htmlspecialchars($result) . '</pre>';
//$row = mysqli_fetch_assoc($result);
//$username = $row['username'];
//echoing username for debug purposes
//echo '<pre>' .htmlspecialchars($username) . '</pre>';
//echo '<pre>' .htmlspecialchars($username) . '</pre>';

$count = mysqli_num_rows($result);

//echoing count for debug purposes, want to make sure it enters in conditional
//echo '<pre>' .htmlspecialchars($count) . '</pre>';

if($count == 1){
    $row = mysqli_fetch_assoc($result); 
    $username = $row['username'];
    //echo '<pre>' .htmlspecialchars($username) . '</pre>';
    $email = $row['email']; 
    //echo '<pre>' .htmlspecialchars($email) . '</pre>';
    $picture = $row['profilepicture'];
    echo '<pre>' .htmlspecialchars($email) . '</pre>';
}else{
    echo "There was an error retrieving the username and email from the database";   
}

//$a = print_r(var_dump($GLOBALS),1);
//echo '<pre>';
//echo htmlspecialchars($a);
//echo '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="styling.css" rel="stylesheet">
      <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
      <style>
        #container{
            margin-top:100px;   
        }

        #notePad, #allNotes, #done{
            display: none;   
        }
           #bgvideo{
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
          }

        .buttons{
            margin-bottom: 20px;   
        }

        textarea{
            width: 100%;
            max-width: 100%;
            font-size: 16px;
            line-height: 1.5em;
            border-left-width: 20px;
            border-color: #CA3DD9;
            color: #CA3DD9;
            background-color: #FBEFFF;
            padding: 10px;
              
        }
          
          tr{
             cursor: pointer;    
          }
          #previewing{
              max-width: 100%;
              height: auto;
              border-radius: 50%;
          }
          .previewing2{
              margin: auto;
              height: 20px;
              border-radius: 50%;
          }
          #spinner{
              display: none;
              position: fixed;
              top: 0;
              left: 0;
              bottom: 0;
              right: 0;
              height: 85px;
              text-align: center;
              margin: auto;
              z-index: 1100;
          }
      </style>
  </head>
  <body>
       <video autoplay loop muted id="bgvideo">
            <source src="traffic.mp4" type="video/mp4">
        </video>
    <!--Navigation Bar-->  
      <nav role="navigation" class="navbar navbar-custom navbar-fixed-top">
      
          <div class="container-fluid">
            
              <div class="navbar-header">
              
                  <a class="navbar-brand">Ride Share</a>
                  <button type="button" class="navbar-toggle" data-target="#navbarCollapse" data-toggle="collapse">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  
                  </button>
              </div>
              <div class="navbar-collapse collapse" id="navbarCollapse">
                  <ul class="nav navbar-nav">
                    <li><a href="index.php">Search</a></li>  
                    <li class="active"><a href="#">Profile</a></li>
                    <li><a href="#helpModal" data-toggle="modal">Help</a></li>
                    <li><a href="#contactModal" data-toggle="modal">Contact us</a></li>
                      <li><a href="mainpageloggedin.php">My Trips</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                      <li><a href="#">
                            <?php
                                if(empty($picture)){
                                    echo "<div class='image_preview'  data-target='#updatepicture' data-toggle='modal'><img class='previewing2' src='profilepicture/noimage.jpg' /></div>";
                                }else{
                                    echo "<div class='image_preview' data-target='#updatepicture' data-toggle='modal'><img class='previewing2' src='$picture' /></div>";
                                }

                              ?>
                          </a>
                      </li>
                      <li><a href="#"><b><?php echo $username; ?></b></a></li>
                    <li><a href="index.php?logout=1">Log out</a></li>
                  </ul>
              
              </div>
          </div>
      
      </nav>
    
<!--Container-->
      <div class="container" id="container">
          <div class="row">
              <div class="col-md-offset-3 col-md-6">

                  <h2 style="color:	white">General Account Settings:</h2>
                  <div class="table-responsive">
                      <table class="table table-hover table-condensed table-bordered">
                          <tr data-target="#updateusername" data-toggle="modal">
                              <td>Username</td>
                              <td><?php echo $username; ?></td>
                          </tr>
                          <tr data-target="#updateemail" data-toggle="modal">
                              <td>Email</td>
                              <td><?php echo $email ?></td>
                          </tr>
                          <tr data-target="#updatepassword" data-toggle="modal">
                              <td>Password</td>
                              <td>hidden</td>
                          </tr>
                      </table>
                  
                  </div>
              
              </div>
          </div>
      </div>

    <!--Update username-->    
      <form method="post" id="updateusernameform">
        <div class="modal" id="updateusername" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Edit Username: 
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--update username message from PHP file-->
                  <div id="updateusernamemessage"></div>
                  

                  <div class="form-group">
                      <label for="username" >Username:</label>
                      <input class="form-control" type="text" name="username" id="username" maxlength="30" value="<?php echo $username; ?>">
                  </div>
                  
              </div>
              <div class="modal-footer">
                  <input class="btn green" name="updateusername" type="submit" value="Submit">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cancel
                </button> 
              </div>
          </div>
      </div>
      </div>
      </form>

    <!--Update email-->    
      <form method="post" id="updateemailform">
        <div class="modal" id="updateemail" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Enter new email: 
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--Update email message from PHP file-->
                  <div id="updateemailmessage"></div>
                  

                  <div class="form-group">
                      <label for="email" >Email:</label>
                      <input class="form-control" type="email" name="email" id="email" maxlength="50" value="<?php echo $email ?>">
                  </div>
                  
              </div>
              <div class="modal-footer">
                  <input class="btn green" name="updateusername" type="submit" value="Submit">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cancel
                </button> 
              </div>
          </div>
      </div>
      </div>
      </form>
      
    <!--Update password-->    
      <form method="post" id="updatepasswordform">
        <div class="modal" id="updatepassword" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Enter Current and New password:
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--Update password message from PHP file-->
                  <div id="updatepasswordmessage"></div>
                  

                  <div class="form-group">
                      <label for="currentpassword" class="sr-only" >Your Current Password:</label>
                      <input class="form-control" type="password" name="currentpassword" id="currentpassword" maxlength="30" placeholder="Your Current Password">
                  </div>
                  <div class="form-group">
                      <label for="password" class="sr-only" >Choose a password:</label>
                      <input class="form-control" type="password" name="password" id="password" maxlength="30" placeholder="Choose a password">
                  </div>
                  <div class="form-group">
                      <label for="password2" class="sr-only" >Confirm password:</label>
                      <input class="form-control" type="password" name="password2" id="password2" maxlength="30" placeholder="Confirm password">
                  </div>
                  
              </div>
              <div class="modal-footer">
                  <input class="btn green" name="updateusername" type="submit" value="Submit">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cancel
                </button> 
              </div>
          </div>
      </div>
      </div>
      </form>
      
      <!--Update picture-->    
      <form method="post" enctype="multipart/form-data" id="updatepictureform">
        <div class="modal" id="updatepicture" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    Upload Picture:
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--Update picture message from PHP file-->
                  <div id="updatepicturemessage"></div>
                  <?php
                    if(empty($picture)){
                        echo "<div class='image_preview'><img id='previewing' src='profilepicture/noimage.jpg' /></div>";
                    }else{
                        echo "<div class='image_preview'><img id='previewing' src='$picture' /></div>";
                    }
    
                  ?>
                  <div class="form-inline">
                      <div class="form-group">
                        <label for="picture">Select a picture:</label>
                        <input type="file" name="picture" id="picture">
                      </div>
                </div>

                  
                  
              </div>
              <div class="modal-footer">
                  <input class="btn green" name="updatepicture" type="submit" value="Submit">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cancel
                </button> 
              </div>
          </div>
      </div>
      </div>
      </form>
      <!--Help modal-->
      <div class="modal" id="helpModal" role="dialog" aria-labelledby="mymodalLabel" aria-hidden="true">
        <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                    <h4 id="myModalLabel">Help</h4>
              </div>
              <div class="modal-body">
                  <p>Transportation is an important yet tricky aspect of daily life. If a person does not own a car, transportation through other means can be quite expensive. Public transportation, while an option, is cost effective but at the expense of time. RideShare provides everyone the chance to supply or demand the ride service whilst being both cost & time effective.</p>
                  <ol>
                    <li>Please edit your profile details here.</li>
                    <li>You can click on Username, Email, and Password containers in order to change your details. </li>
                  </ol>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                  Cancel
                </button>  
              </div>
            </div>
          </div>
      </div>
                      
    <!--Contact Us modal-->
      <form method="post" id="contactform">
        <div class="modal" id="contactModal" role="dialog" aria-labelledby="mymodalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <form action="#"  id="contactForm" method="post" name="contactForm">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">Contact Us</h4>
                </div>
                  <div class="modal-body">
                    <div class="form-group">
                        
                        <label for="contactemail">Email:</label>
                        <input class="form-control" type="email" name="contactemail" id="contactemail" placeholder="Email" maxlength="50" value="">
                        
                    </div>
                      <div class="form-group">
                    <label for="message">Message: </label>
                      <textarea name="message" class="form-control" rows="5" maxlength="300"></textarea>
                      </div>
                  </div>
                    <div class="modal-footer">
                        <input id="send" class="btn green" name="submit" type="submit" value="Submit">
                        
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                        </button>
                    </div>
                      </form>
                  <?php
                    if(isset($_POST["submit"])){
                        //Check for black field
                        if($_POST["contactemail"]==""||$_POST["message"]==""){
                            echo "Invalid. Please fill out both Email and Message Fields.";
                            echo "<script type='text/javascript'>alert('Invalid. Please fill out both Email and Message Fields.');</script>";
                        }else{
                            $email = $_POST['contactemail'];
                            //Filters and sanitizes email data entry
                            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                            $email= filter_var($email, FILTER_VALIDATE_EMAIL);
                            $headers = 'From:'. $email . "rn";
                            if(!$email){
                                echo "Invalid Email. Please enter a valid email.";
                                echo "<script type='text/javascript'>alert('Invalid Email. Please enter a valid email.');</script>";
                                
                            }else{
                                $subject  = "Contact Form";
                                $message = $_POST['message'];
                                mail("cs441rideshare@gmail.com", $subject,$message,$headers);
                                echo "<script type='text/javascript'>alert('Email sent, we will contact you within 7 business days.');</script>";
                            }
                        }
                    }
                  ?>
              </div>
          </div>
        </div>
      </form>
    <!-- Footer-->
      <div class="footer">
          <div class="container">
              <p>cs441rideshare.com Copyright &copy;<?php $today = date("Y"); echo $today?>.</p>
          </div>
      </div>
      <!--Spinner-->
      <div id="spinner">
         <img src='ajax-loader.gif' width="64" height="64" />
         <br>Loading..
      </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
      <script src="profile.js"></script>
  </body>
</html>