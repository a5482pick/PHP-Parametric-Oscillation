<!DOCTYPE html>

<?php include 'OscillatorEmbed.php'; ?>

<html>
<head>
<link rel='stylesheet' media='screen and (min-width: 670px)' href='Oscillator.css' />
<link rel='stylesheet' media='screen and (max-width: 669px)' href='Oscillator2.css' />
<script>
"use strict";
window.addEventListener("resize", drawParametric, false);
window.addEventListener("load", drawParametric, false);
    
var canvasOne, contextOne, time, z1, z2, graph, dataObject, dataArray;

//This array will store all the graph's data.  It's added for 
//completeness but is not used in this implementation.
dataObject = [];

   
//Draw a graph of the combined motions.
function drawParametric()   {
   
    canvasOne = document.getElementById("canvasOne");

    //Choose the appropriate stylesheet for the given window dimensions.
    if (window.innerWidth < 670)  {
    
        canvasOne.width = 300;
        canvasOne.height = 300;
    }
    else  {
        
        //Allow the canvas to resize and fill more of the screen.
        canvasOne.width = 470 * window.innerWidth/1366;
        canvasOne.height = 470 * window.innerWidth/1366;
    }
    
    //Set up the canvas.
    contextOne = canvasOne.getContext("2d");

    contextOne.fillStyle ='#FFFFFF';
    contextOne.fillRect(0,0,canvasOne.width,canvasOne.height);
    contextOne.strokeStyle = '#808080';
    contextOne.strokeRect(1,1,canvasOne.width-2,canvasOne.height-2);
   
    contextOne.beginPath();
    contextOne.moveTo(canvasOne.width/2,0);
    contextOne.lineTo(canvasOne.width/2,canvasOne.height);
    contextOne.strokeStyle = "#00FF00";
    contextOne.stroke();
   
    contextOne.beginPath();
    contextOne.moveTo(0,canvasOne.height/2);
    contextOne.lineWidth = 1;
    contextOne.lineTo(canvasOne.width,canvasOne.height/2);
    contextOne.strokeStyle = "#00FF00";
    contextOne.stroke();
   
    time = 0;
    
    
    //Gather data points for the set amount of time.
    while (time < 100) {
      
        //z1 is the parametric motion of mass 1.  z2 is the parametric motion of mass 2.
        z1 = <?php echo $a ?>*<?php echo $eigen[2] ?>*Math.cos(<?php echo $eigen[0] ?>*time) - <?php echo $a ?>*<?php echo $eigen[3] ?>*Math.cos(<?php echo $eigen[1] ?>*time); 
        z2 = <?php echo $a ?>*Math.cos(<?php echo $eigen[0] ?>*time) - <?php echo $a ?>*Math.cos(<?php echo $eigen[1] ?>*time);
     
        //The graph points are 1 pixel
        graph = contextOne.createImageData(1,1); 
        
        //The points are of colour red.
        for (var i = 0; i < graph.data.length; i += 4) {
      
            graph.data[i+0] = 255;
            graph.data[i+1] = 0;
            graph.data[i+2] = 0;
            graph.data[i+3] = 255;
        }
        
        //Plot (z1,z2) as the (x,y) components on the canvas graph.
        z1 = z1*(canvasOne.height/(2*<?php echo $initialDisp ?>)) + (canvasOne.height/2);
        z2 = z2*(canvasOne.width/(2*<?php echo $initialDisp ?>)) + (canvasOne.width/2);
        contextOne.putImageData(graph, z1, z2);
        
        //The following five lines store the graph data.  They are not used,
        //but could be of use if other information is to be extracted.    
        dataArray = []; 
        dataArray[0] = time;
        dataArray[1] = z1;
        dataArray[2] = z2;
        dataObject.push(dataArray);  //Create 2d array i.e. array of data point sets.
        
        time =  time + 0.001;
    }   
    
    
    //Notify the user of various values.
    document.getElementById("values").innerHTML = "Your submitted values were:    <?php echo $k1, ", ", $k2, ", ". $k3, ", ", $m1, ", ", $m2 ?>.";
    document.getElementById("omega1").innerHTML = "The first 'eigenfrequency' is:    <?php echo round($eigen[0],2) ?>.";
    document.getElementById("omega2").innerHTML = "The second 'eigenfrequency' is:    <?php echo round($eigen[1],2) ?>.";
    document.getElementById("eigen1").innerHTML = "The first 'eigenvector' is:    [<?php echo round($eigen[2],2) ?>,1].";
    document.getElementById("eigen2").innerHTML = "The second 'eigenvector' is:    [<?php echo round($eigen[3],2) ?>,1].";
}
</script>

</head>

<body>

<canvas id="canvasOne"></canvas>

<p id="p1"> A Parametric Plot Of The Motion Of Two Masses Connected By Three Springs.</p>
<p id="p2">The two axes correspond to the two masses.  Time evolves along the curve.</p>

<form id="form1" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" autocomplete="off"> 
    <div id="util1">
        Spring constant k1: <input type="text" name="k1" value="1">
        <br><br>
        Spring constant k2: <input type="text" name="k2" value="1">
        <br><br>
        Spring constant k3: <input type="text" name="k3" value="1">
        <br><br>
        Mass m1: <input type="text" name="m1" value="1">
        <br><br>
        Mass m2: <input type="text" name="m2" value="1">
        <br><br>
        <div id="popout1">Feel free to try different values.  (NOTE: There is a small time delay after submitting.)</div>
    </div>
    <input type="submit" name="submit" value="Submit"> 
</form>

<div id="eigenWrapper">
    <div id="values"></div>
    <div id="omega1"></div>
    <div id="omega2"></div>
    <div id="eigen1"></div>
    <div id="eigen2"></div>
</div>

</body>
</html>
