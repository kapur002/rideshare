<?php
session_start();
include('connection.php');
if(!isset($_SESSION['user_id'])){
    header("location: index.php");
}
?>

<?php
$user_id = $_SESSION['user_id'];

//get username and email
$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($link, $sql);

$count = mysqli_num_rows($result);

if($count == 1){
    //$row = mysqli_fetch_array($result, MYSQL_ASSOC);
    $row = mysqli_fetch_assoc($result); 
    $username = $row['username'];
    $picture = $row['profilepicture'];
}else{
    echo "There was an error retrieving the username and email from the database";   
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Trips</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/sunny/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="styling.css" rel="stylesheet">
      <link href='https://fonts.googleapis.com/css?family=Arvo' rel='stylesheet' type='text/css'>
      <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDKpjZvs9OSrggqbGODALmU4NZBKsRUfqQ"></script>
      <style>
        #container{
            margin-top:120px;   
        }

        #notePad, #allNotes, #done, .delete{
            display: none;   
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
        
        .noteheader{
            border: 1px solid grey;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            padding: 0 10px;
            background: linear-gradient(#FFFFFF,#ECEAE7);
        }
          
        .text{
            font-size: 20px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
          
        .timetext{
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .notes{
            margin-bottom: 100px;
        }
          
        #googleMap{
            width: 300px;
            height: 200px;
            margin: 30px auto;
        }
          #bgvideo{
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
          }
        .modal{
            z-index: 20;   
        }
        .modal-backdrop{
            z-index: 10;        
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
        .checkbox{
            margin-bottom: 10px;   
        }
        .trip{
            border:1px solid grey;
            border-radius: 10px;
            margin-bottom:10px;
            background: linear-gradient(#ECE9E6, #FFFFFF);
            padding: 10px;
        }
        .price{
            font-size:1.5em;
        }
        .departure, .destination{
            font-size:1.5em;
        } 
        .perseat{
            font-size:0.5em;
    /*        text-align:right;*/
        }
        .time{
            margin-top:10px;  
        }  
        .notrips{
            text-align:center;
        }
        .trips{
            margin-top: 20px;
        }
        .previewing2{
            margin: auto;
            height: 20px;
            border-radius: 50%;
        }
          #mytrips{
            margin-bottom: 100px;   
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
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="#helpModal" data-toggle="modal">Help</a></li>
                    <li><a href="#contactModal" data-toggle="modal">Contact us</a></li>
                      <li class="active"><a href="#">My Trips</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                      <li><a href="#">
                    <?php
                        if(empty($picture)){
                            echo "<div class='image_preview'><img class='previewing2' src='profilepicture/noimage.jpg' /></div>";
                        }else{
                            echo "<div class='image_preview'><img class='previewing2' src='$picture' /></div>";
                        }

                      ?>
                  </a>
              </li>
              <li><a href="#"><b><?php echo $username?></b></a></li>
                    <li><a href="index.php?logout=1">Log out</a></li>
                  </ul>
              
              </div>
          </div>
      
      </nav>
    
<!--Container-->
      <div class="container" id="container">
          <div class="row">
              <div class="col-sm-8 col-sm-offset-2">
                  <div>
                      <button type="button" class="btn green btn-lg" data-target="#addtripModal" data-toggle="modal">
                          Add trips
                      </button>
                  </div>
                  <div id="mytrips" class="trips">
                      <!--Ajax Call to php file-->
                  </div>
              </div>

          </div>
      </div>
      
      <!--Add a trip form-->
      <form method="post" id="addtripform">
        <div class="modal" id="addtripModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel">
                    New trip:
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--Error message from PHP file-->
                  <div id="result"></div>
                  
                  <!--Google Map-->
                  <div id="googleMap"></div>
                  
                <div class="form-group">
                    <label for="departure" class="sr-only">Departure:</label>
                    <input type="text" name="departure" id="departure" placeholder="Departure" class="form-control">
                </div>
                <div class="form-group">
                    <label for="destination" class="sr-only">Destination:</label>
                    <input type="text" name="destination" id="destination" placeholder="Destination" class="form-control">
                </div>
                <div class="form-group">
                    <label for="price" class="sr-only">Price:</label>
                    <input type="number" name="price" id="price" placeholder="Price" class="form-control">
                </div> 
                <div class="form-group">
                    <label for="seatsavailable" class="sr-only">Seats available:</label>
                    <input type="number" name="seatsavailable" id="seatsavailable" placeholder="Seats available" class="form-control">
                </div>  
              <div  class="form-group">
                    <label><input type="radio" name="regular" id="yes" value="Y">Regular</label>    
                    <label><input type="radio" name="regular" id="no" value="N">One-off</label>    
                </div>
                <div class="checkbox checkbox-inline regular">
                    <label><input type="checkbox" value="1" id="monday" name="monday"> Monday</label>    
                    <label><input type="checkbox" value="1" id="tuesday" name="tuesday"> Tuesday</label>    
                    <label><input type="checkbox" value="1" id="wednesday" name="wednesday"> Wednesday</label>    
                    <label><input type="checkbox" value="1" id="thursday" name="thursday"> Thursday</label>    
                    <label><input type="checkbox" value="1" id="friday" name="friday"> Friday</label>    
                    <label><input type="checkbox" value="1" id="saturday" name="saturday"> Saturday</label>    
                    <label><input type="checkbox" value="1" id="sunday" name="sunday"> Sunday</label>    
                </div>  
                <div class="form-group oneoff">
                    <label for="date"  class="sr-only">Date: </label>    
                    <input name="date" id="date" readonly="readonly" placeholder="Date"  class="form-control">
                </div>  
                <div class="form-group time regular oneoff">
                    <label for="time" class="sr-only">Time: </label>    
                    <input type="time" name="time" id="time" placeholder="Time"  class="form-control">
                </div>  
              </div>
              <div class="modal-footer">
                <input class="btn btn-primary" name="createTrip" type="submit" value="Create Trip">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
      </div>
      </div>
      </form>

      <!--Edit a trip form-->
      <form method="post" id="edittripform">
        <div class="modal" id="edittripModal" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button class="close" data-dismiss="modal">
                    &times;
                  </button>
                  <h4 id="myModalLabel2">
                    Edit trip:
                  </h4>
              </div>
              <div class="modal-body">
                  
                  <!--Error message from PHP file-->
                  <div id="result2"></div>
                  
                <div class="form-group">
                    <label for="departure2" class="sr-only">Departure:</label>
                    <input type="text" name="departure2" id="departure2" placeholder="Departure" class="form-control">
                </div>
                <div class="form-group">
                    <label for="destination2" class="sr-only">Destination:</label>
                    <input type="text" name="destination2" id="destination2" placeholder="Destination" class="form-control">
                </div>
                <div class="form-group">
                    <label for="price2" class="sr-only">Price:</label>
                    <input type="number" name="price2" id="price2" placeholder="Price" class="form-control">
                </div> 
                <div class="form-group">
                    <label for="seatsavailable2" class="sr-only">Seats available:</label>
                    <input type="number" name="seatsavailable2" placeholder="Seats available" class="form-control" id="seatsavailable2">
                </div>  
              <div  class="form-group">
                    <label><input type="radio" name="regular2" id="yes2" value="Y">Regular</label>    
                    <label><input type="radio" name="regular2" id="no2" value="N">One-off</label>    
                </div>
                <div class="checkbox checkbox-inline regular2">
                    <label><input type="checkbox" value="1" id="monday2" name="monday2"> Monday</label>    
                    <label><input type="checkbox" value="1" id="tuesday2" name="tuesday2"> Tuesday</label>    
                    <label><input type="checkbox" value="1" id="wednesday2" name="wednesday2"> Wednesday</label>    
                    <label><input type="checkbox" value="1" id="thursday2" name="thursday2"> Thursday</label>    
                    <label><input type="checkbox" value="1" id="friday2" name="friday2"> Friday</label>    
                    <label><input type="checkbox" value="1" id="saturday2" name="saturday2"> Saturday</label>    
                    <label><input type="checkbox" value="1" id="sunday2" name="sunday2"> Sunday</label>    
                </div>  
                <div class="form-group oneoff2">
                    <input name="date2" id="date2" readonly="readonly" placeholder="Date"  class="form-control">
                </div>  
                <div class="form-group time regular2 oneoff2">
                    <label for="time2" class="sr-only">Time: </label>    
                    <input type="time" name="time2" id="time2" placeholder="Time"  class="form-control">
                </div>  
              </div>
              <div class="modal-footer">
                <input class="btn btn-primary" name="updatetrip" type="submit" id="updatetrip" value="Edit Trip">
                <input type="button" class="btn btn-danger" name="deletetrip" value="Delete" id="deletetrip">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
      </div>
      </div>
      </form>
<!--      MODALS SECTION-->
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
                    <li>You can <a href="#addtripModal" data-toggle="modal">Add a Trip</a> in the My Trips tab.</li>
                    <li>View and manage your User <a href="profile.php">Profile</a></li>
                    <li>When you add a trip you can specify the dates and times for the route.</li>
                    <li>Simply input your Departure, Destination, Price, and Seats available. Then specify if you this is a Regular Route or a One-off trip.</li>
                    <li>Feel free to <a href="#contactModal" data-toggle="modal">Contact Us</a> for any additional questions.</li>
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
              <p>cs441rideshare.com Copyright &copy; <?php $today = date("Y"); echo $today?>.</p>
          </div>
      </div>
      
      <!--Spinner-->
      <div id="spinner">
         <img src='ajax-loader.gif' width="64" height="64" />
         <br>Loading..
      </div>


    <script src="map.js"></script>  
    <script src="mytrips.js"></script>  
  </body>
</html>