<?php
include_once('connection.php');
if(isset($_POST['name']) && isset($_POST['pnt']) && isset($_POST['opr'])){
    $name=$_POST['name'];
    $pnt=$_POST['pnt'];
    $opr=$_POST['opr'];
    $query="SELECT * FROM points WHERE name LIKE '" . $name ."'";
            $resultx = mysqli_query($GLOBALS['connection'], $query);
            if(!$resultx){
                die('Query Failed' . mysqli_error($GLOBALS['connection']));
            }   
            while($row=mysqli_fetch_assoc($resultx)){
                $points = $row['points'];
                if($opr=="add"){
                    $final=$points + $pnt;
                    $task="Added to";
                }
                if($opr=="sub"){
                    $final=$points - $pnt;
                    $task="Subtracted from";
                }
                $query2="UPDATE points SET points='". $final ."' WHERE name LIKE '" . $name ."'";
            $resultx2 = mysqli_query($GLOBALS['connection'], $query2);
            if(!$resultx2){
                die('Query Failed' . mysqli_error($GLOBALS['connection']));
            }
                

?>

<html>
    <head>
            <link href="https://fonts.googleapis.com/css?family=Oswald:400&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Fjalla+One&display=swap" rel="stylesheet"> 
    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/1.0.0/anime.min.js"></script>
        <style>
            canvas {
  display: block;
  width: 100%;
  height: 100%;
  margin:0;
  padding:0;
}
.form-title{


                font-family: 'Fjalla One', sans-serif;


        }
body{
    padding:0;
}
        </style>
        <title>Success - Points Updater</title>
    </head>
    <body>
        
        <canvas id="c" style="z-index:1;"></canvas>
        <div class="main" style="position:fixed; top:40px; left:30%;">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <form method="POST" id="signup-form" action="submit.php" class="signup-form needs-validation" target="_blank">
                    <h2 class="form-title"><?php echo $pnt; ?> points <?php echo $task; ?></h2>
                    <h2 class="form-title"><?php echo $name; ?></h2>
                    <br>
                    <h2 class="form-title">Do for another one</h2>
                    
                        <div class="form-group">
                            <input type="text" class="form-input" name="pnt" id="name" placeholder="Points" required>
                            
                            
                        </div>
                        <div class="form-group">
                            <select class="form-input" name="opr" id="name" placeholder="Academy" required>
                                <option value="add">Add</option>
                                <option value="sub">Substract</option>
                                
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-input" name="name" id="name" placeholder="Academy" required>
                                <option value="jedi">Jedi Academy</option>
                                <option value="hogwarts">Hogwarts</option>
                                <option value="xmansion">X-Mansion</option>
                                <option value="umbrella">Umbrella Academy</option>
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" id="submit" class="form-submit" value="Submit" />
                            
                        </div>
                        
                    </form>
                    </div>
                    
            </div>
        </section>
    </div>
        <script>
            var c = document.getElementById("c");
var ctx = c.getContext("2d");
var cH;
var cW;
var bgColor = "#FF6138";
var animations = [];
var circles = [];

var colorPicker = (function() {
  var colors = ["#FF6138", "#FFBE53", "#2980B9", "#282741", "#8E0E00", "#76b852", "#673AB7", "#00C9FF", "#7D6EEE", "#2EB3E4", "#136a8a"];
  var index = 0;
  function next() {
    index = index++ < colors.length-1 ? index : 0;
    return colors[index];
  }
  function current() {
    return colors[index]
  }
  return {
    next: next,
    current: current
  }
})();

function removeAnimation(animation) {
  var index = animations.indexOf(animation);
  if (index > -1) animations.splice(index, 1);
}

function calcPageFillRadius(x, y) {
  var l = Math.max(x - 0, cW - x);
  var h = Math.max(y - 0, cH - y);
  return Math.sqrt(Math.pow(l, 2) + Math.pow(h, 2));
}

function addClickListeners() {
  document.addEventListener("touchstart", handleEvent);
  document.addEventListener("mousedown", handleEvent);
};

function handleEvent(e) {
    if (e.touches) { 
      e.preventDefault();
      e = e.touches[0];
    }
    var currentColor = colorPicker.current();
    var nextColor = colorPicker.next();
    var targetR = calcPageFillRadius(e.pageX, e.pageY);
    var rippleSize = Math.min(400, (cW * .4));
    var minCoverDuration = 1050;
    
    var pageFill = new Circle({
      x: e.pageX,
      y: e.pageY,
      r: 0,
      fill: nextColor
    });
    var fillAnimation = anime({
      targets: pageFill,
      r: targetR,
      duration:  Math.max(targetR / 2 , minCoverDuration ),
      easing: "easeOutQuart",
      complete: function(){
        bgColor = pageFill.fill;
        removeAnimation(fillAnimation);
      }
    });
    
    var ripple = new Circle({
      x: e.pageX,
      y: e.pageY,
      r: 0,
      fill: currentColor,
      stroke: {
        width: 3,
        color: currentColor
      },
      opacity: 1
    });
    var rippleAnimation = anime({
      targets: ripple,
      r: rippleSize,
      opacity: 0,
      easing: "easeOutExpo",
      duration: 2000,
      complete: removeAnimation
    });
    
    var particles = [];
    for (var i=0; i<50; i++) {
      var particle = new Circle({
        x: e.pageX,
        y: e.pageY,
        fill: currentColor,
        r: anime.random(24, 48)
      })
      particles.push(particle);
    }
    var particlesAnimation = anime({
      targets: particles,
      x: function(particle){
        return particle.x + anime.random(rippleSize, -rippleSize);
      },
      y: function(particle){
        return particle.y + anime.random(rippleSize * 1.15, -rippleSize * 1.15);
      },
      r: 0,
      easing: "easeOutExpo",
      duration: anime.random(1000,1300),
      complete: removeAnimation
    });
    animations.push(fillAnimation, rippleAnimation, particlesAnimation);
}

function extend(a, b){
  for(var key in b) {
    if(b.hasOwnProperty(key)) {
      a[key] = b[key];
    }
  }
  return a;
}

var Circle = function(opts) {
  extend(this, opts);
}

Circle.prototype.draw = function() {
  ctx.globalAlpha = this.opacity || 1;
  ctx.beginPath();
  ctx.arc(this.x, this.y, this.r, 0, 2 * Math.PI, false);
  if (this.stroke) {
    ctx.strokeStyle = this.stroke.color;
    ctx.lineWidth = this.stroke.width;
    ctx.stroke();
  }
  if (this.fill) {
    ctx.fillStyle = this.fill;
    ctx.fill();
  }
  ctx.closePath();
  ctx.globalAlpha = 1;
}

var animate = anime({
  duration: Infinity,
  update: function() {
    ctx.fillStyle = bgColor;
    ctx.fillRect(0, 0, cW, cH);
    animations.forEach(function(anim) {
      anim.animatables.forEach(function(animatable) {
        animatable.target.draw();
      });
    });
  }
});

var resizeCanvas = function() {
  cW = window.innerWidth;
  cH = window.innerHeight;
  c.width = cW * devicePixelRatio;
  c.height = cH * devicePixelRatio;
  ctx.scale(devicePixelRatio, devicePixelRatio);
};

(function init() {
  resizeCanvas();
  if (window.CP) {
    // CodePen's loop detection was causin' problems
    // and I have no idea why, so...
    window.CP.PenTimer.MAX_TIME_IN_LOOP_WO_EXIT = 6000; 
  }
  window.addEventListener("resize", resizeCanvas);
  addClickListeners();
  if (!!window.location.pathname.match(/fullcpgrid/)) {
    startFauxClicking();
  }
  handleInactiveUser();
})();

function handleInactiveUser() {
  var inactive = setTimeout(function(){
    fauxClick(cW/2, cH/2);
  }, 2000);
  
  function clearInactiveTimeout() {
    clearTimeout(inactive);
    document.removeEventListener("mousedown", clearInactiveTimeout);
    document.removeEventListener("touchstart", clearInactiveTimeout);
  }
  
  document.addEventListener("mousedown", clearInactiveTimeout);
  document.addEventListener("touchstart", clearInactiveTimeout);
}

function startFauxClicking() {
  setTimeout(function(){
    fauxClick(anime.random( cW * .2, cW * .8), anime.random(cH * .2, cH * .8));
    startFauxClicking();
  }, anime.random(200, 900));
}

function fauxClick(x, y) {
  var fauxClick = new Event("mousedown");
  fauxClick.pageX = x;
  fauxClick.pageY = y;
  document.dispatchEvent(fauxClick);
}
        </script>
    </body>
</html>




<?php
}
}else{
    
?>

<html>
    <head>
            <link href="https://fonts.googleapis.com/css?family=Oswald:400&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Fjalla+One&display=swap" rel="stylesheet"> 
    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/1.0.0/anime.min.js"></script>
        <style>
            canvas {
  display: block;
  width: 100%;
  height: 100%;
  margin:0;
  padding:0;
}
.form-title{


                font-family: 'Fjalla One', sans-serif;


        }
body{
    padding:0;
}
@media only screen and (max-width: 600px) {
  .main {
    position:fixed; top:40px;
    margin:10%;
  }
}
@media only screen and (min-width: 600px) {
  .main {
    position:fixed; top:40px;
    left:30%;
  }
}
        </style>
        <title>Points Adder</title>
        <meta name="viewport" content= "width=device-width, initial-scale=1.0"> 

    </head>
    <body>
        
        <canvas id="c" style="z-index:1;"></canvas>
        <div class="main" style="">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <form method="POST" id="signup-form" action="submit.php" class="signup-form needs-validation">
                    <h2 class="form-title">Points Adder</h2>
                        <div class="form-group">
                            <input type="text" class="form-input" name="pnt" id="name" placeholder="Points" required>
                            
                            
                        </div>
                        <div class="form-group">
                            <select class="form-input" name="opr" id="name" placeholder="Academy" required>
                                <option value="add">Add</option>
                                <option value="sub">Substract</option>
                                
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-input" name="name" id="name" placeholder="Academy" required>
                                <option value="jedi">Jedi Academy</option>
                                <option value="hogwarts">Hogwarts</option>
                                <option value="xmansion">X-Mansion</option>
                                <option value="umbrella">Umbrella Academy</option>
                                
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" id="submit" class="form-submit" value="Submit" />
                            
                        </div>
                        
                    </form>
                    </div>
                    
            </div>
        </section>
    </div>
        <script>
            var c = document.getElementById("c");
var ctx = c.getContext("2d");
var cH;
var cW;
var bgColor = "#FF6138";
var animations = [];
var circles = [];

var colorPicker = (function() {
  var colors = ["#FF6138", "#FFBE53", "#2980B9", "#282741", "#8E0E00", "#76b852", "#673AB7", "#00C9FF", "#7D6EEE", "#2EB3E4", "#136a8a"];
  var index = 0;
  function next() {
    index = index++ < colors.length-1 ? index : 0;
    return colors[index];
  }
  function current() {
    return colors[index]
  }
  return {
    next: next,
    current: current
  }
})();

function removeAnimation(animation) {
  var index = animations.indexOf(animation);
  if (index > -1) animations.splice(index, 1);
}

function calcPageFillRadius(x, y) {
  var l = Math.max(x - 0, cW - x);
  var h = Math.max(y - 0, cH - y);
  return Math.sqrt(Math.pow(l, 2) + Math.pow(h, 2));
}

function addClickListeners() {
  document.addEventListener("touchstart", handleEvent);
  document.addEventListener("mousedown", handleEvent);
};

function handleEvent(e) {
    if (e.touches) { 
      e.preventDefault();
      e = e.touches[0];
    }
    var currentColor = colorPicker.current();
    var nextColor = colorPicker.next();
    var targetR = calcPageFillRadius(e.pageX, e.pageY);
    var rippleSize = Math.min(400, (cW * .4));
    var minCoverDuration = 1050;
    
    var pageFill = new Circle({
      x: e.pageX,
      y: e.pageY,
      r: 0,
      fill: nextColor
    });
    var fillAnimation = anime({
      targets: pageFill,
      r: targetR,
      duration:  Math.max(targetR / 2 , minCoverDuration ),
      easing: "easeOutQuart",
      complete: function(){
        bgColor = pageFill.fill;
        removeAnimation(fillAnimation);
      }
    });
    
    var ripple = new Circle({
      x: e.pageX,
      y: e.pageY,
      r: 0,
      fill: currentColor,
      stroke: {
        width: 3,
        color: currentColor
      },
      opacity: 1
    });
    var rippleAnimation = anime({
      targets: ripple,
      r: rippleSize,
      opacity: 0,
      easing: "easeOutExpo",
      duration: 2000,
      complete: removeAnimation
    });
    
    var particles = [];
    for (var i=0; i<50; i++) {
      var particle = new Circle({
        x: e.pageX,
        y: e.pageY,
        fill: currentColor,
        r: anime.random(24, 48)
      })
      particles.push(particle);
    }
    var particlesAnimation = anime({
      targets: particles,
      x: function(particle){
        return particle.x + anime.random(rippleSize, -rippleSize);
      },
      y: function(particle){
        return particle.y + anime.random(rippleSize * 1.15, -rippleSize * 1.15);
      },
      r: 0,
      easing: "easeOutExpo",
      duration: anime.random(1000,1300),
      complete: removeAnimation
    });
    animations.push(fillAnimation, rippleAnimation, particlesAnimation);
}

function extend(a, b){
  for(var key in b) {
    if(b.hasOwnProperty(key)) {
      a[key] = b[key];
    }
  }
  return a;
}

var Circle = function(opts) {
  extend(this, opts);
}

Circle.prototype.draw = function() {
  ctx.globalAlpha = this.opacity || 1;
  ctx.beginPath();
  ctx.arc(this.x, this.y, this.r, 0, 2 * Math.PI, false);
  if (this.stroke) {
    ctx.strokeStyle = this.stroke.color;
    ctx.lineWidth = this.stroke.width;
    ctx.stroke();
  }
  if (this.fill) {
    ctx.fillStyle = this.fill;
    ctx.fill();
  }
  ctx.closePath();
  ctx.globalAlpha = 1;
}

var animate = anime({
  duration: Infinity,
  update: function() {
    ctx.fillStyle = bgColor;
    ctx.fillRect(0, 0, cW, cH);
    animations.forEach(function(anim) {
      anim.animatables.forEach(function(animatable) {
        animatable.target.draw();
      });
    });
  }
});

var resizeCanvas = function() {
  cW = window.innerWidth;
  cH = window.innerHeight;
  c.width = cW * devicePixelRatio;
  c.height = cH * devicePixelRatio;
  ctx.scale(devicePixelRatio, devicePixelRatio);
};

(function init() {
  resizeCanvas();
  if (window.CP) {
    // CodePen's loop detection was causin' problems
    // and I have no idea why, so...
    window.CP.PenTimer.MAX_TIME_IN_LOOP_WO_EXIT = 6000; 
  }
  window.addEventListener("resize", resizeCanvas);
  addClickListeners();
  if (!!window.location.pathname.match(/fullcpgrid/)) {
    startFauxClicking();
  }
  handleInactiveUser();
})();

function handleInactiveUser() {
  var inactive = setTimeout(function(){
    fauxClick(cW/2, cH/2);
  }, 2000);
  
  function clearInactiveTimeout() {
    clearTimeout(inactive);
    document.removeEventListener("mousedown", clearInactiveTimeout);
    document.removeEventListener("touchstart", clearInactiveTimeout);
  }
  
  document.addEventListener("mousedown", clearInactiveTimeout);
  document.addEventListener("touchstart", clearInactiveTimeout);
}

function startFauxClicking() {
  setTimeout(function(){
    fauxClick(anime.random( cW * .2, cW * .8), anime.random(cH * .2, cH * .8));
    startFauxClicking();
  }, anime.random(200, 900));
}

function fauxClick(x, y) {
  var fauxClick = new Event("mousedown");
  fauxClick.pageX = x;
  fauxClick.pageY = y;
  document.dispatchEvent(fauxClick);
}
        </script>
    </body>
</html>
    <?php
}


?>