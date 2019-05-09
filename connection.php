<?php
//mysqli_connect("127.0.0.1", "my_user", "my_password", "my_db")
$link = mysqli_connect("localhost", "cs441rid_admin", "PaKa22940121", "cs441rid_db");
if(mysqli_connect_error()){
    die('ERROR: Unable to connect:' . mysqli_connect_error()); 
    echo "<script>window.alert('Hi!')</script>";
}
    ?>