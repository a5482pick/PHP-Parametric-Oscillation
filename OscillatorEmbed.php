<?php

//This function returns the eigenfrequencies and eigenvectors in an array.
//The 2nd vector component is not calculated/returned because it is always 1.
function eigenstate($k1, $k2, $k3, $m1, $m2)   {

    //Calculate the eigenvalues.          
    $lamdaBlock1 = $k1*$m2 + $k2*$m1 + $k2*$m2 + $k3*$m1;
    $lamdaBlock2 = $k1*$k2*$m1*$m2 + $k1*$k3*$m1*$m2 + $k2*$k3*$m1*$m2;
    $lamdaBlock3 = $k1*$m2 + $k2*$m1 + $k2*$m2 + $k3*$m1;
      
    $lamdaSQRT = sqrt(pow($lamdaBlock1,2) - 4*$lamdaBlock2);
      
    $lamda1 = ((-$lamdaSQRT) - $lamdaBlock3) / (2*$m1*$m2);
    $lamda2 = ($lamdaSQRT - $lamdaBlock3) / (2*$m1*$m2); 
      
    $omega1 = sqrt(-$lamda1);
    $omega2 = sqrt(-$lamda2);
   
   
    //Calculate the eigenvectors.  The 2nd vector component is 1.   
    $vectorBlock1 = pow($k2*$m1 + $k3*$m1 + $k1*$m2 + $k2*$m2,2);
    $vectorBlock2 = $k1*$k2*$m1*$m2 + $k1*$k3*$m1*$m2 + $k2*$k3*$m1*$m2;
    $vectorBlock3 = $k2*$m1 + $k3*$m1 + $k1*$m2 + $k2*$m2;
     
    $eigen1 = 0.5*($vectorBlock3 + sqrt($vectorBlock1 - 4*$vectorBlock2));
    $eigen1 = -$m1*($k2 + $k3) + $eigen1;
    $eigen1 = (-1/($k2*$m1))*$eigen1;
      
    $eigen2 = 0.5*($vectorBlock3 - sqrt($vectorBlock1 - 4*$vectorBlock2));
    $eigen2 = -$m1*($k2 + $k3) + $eigen2;
    $eigen2 = (-1/($k2*$m1))*$eigen2;
    
    $eigen = array($omega1, $omega2, $eigen1, $eigen2);
    return $eigen;
} 

// define variables and set to empty values
$k1 = $k2 = $k3 = $m1 = $m2 = "";

//Listen for submitted values from the html form.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $k1 = test_input($_POST["k1"]);
    $k2 = test_input($_POST["k2"]);
    $k3 = test_input($_POST["k3"]);
    $m1 = test_input($_POST["m1"]);
    $m2 = test_input($_POST["m2"]);
}


//Test the submitted data for format anomalies.
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    
    return $data;
}


//Ensure the variables are of type float.
$k1 = floatval($k1);
$k2 = floatval($k2);
$k3 = floatval($k3);
$m1 = floatval($m1);
$m2 = floatval($m2);


//Calculate the eigenmodes for the submitted values.
$eigen = eigenstate($k1, $k2, $k3, $m1, $m2);


//set the initial amplitude. 
$initialDisp = 1.0;
$a = $initialDisp / ($eigen[2] - $eigen[3]);

?>

