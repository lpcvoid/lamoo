<?php
/**
 * Created by PhpStorm.
 * User: Swoosh
 * Date: 26.04.16
 * Time: 16:52
 */

//error_reporting(E_ALL);
//ini_set ('display_errors', '1');

//phpinfo();

ini_set('xdebug.var_display_max_depth', 7);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

ini_set("max_execution_time",300);

require_once "lmoLovooCredentials.php";
require_once "lmoLovooConnector.php";
require_once "lmoLovooScraper.php";



$cred = new lmoLovooCredentials("nope","nope");
$con = new lmoLovooConnector($cred);

$con->GetAuthCookies();
$c = $con->AttemptAuth();
$u = $con->GetUserDetails("nope");


$users = $con->GetUsers(18,30,"true",1);




$scrapper = new lmoLovooScraper($con,"lovoo.sqlite");
$scrapper->Scrape();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Lamoo</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Lamoo</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Lamoo</a></li>
                <li class="active"><a href="#">Listview</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">

    <div class="starter-template">
        <h1>Lame lovoo... Lamoo</h1>
        <p class="lead">Dumping chicks...</p>
    </div>


    <div class="jumbotron">


        <pre>
            <?php
            var_dump($u->response->result);
            ?>

        </pre>

        <?php




        foreach ($users->response->result as $key => $value) {

            echo $value->name . "\n";
            //echo $value->id . "\n";
            echo '<img src="https://img.lovoo.com/users/pictures/'.$value->picture.'/thumb_l.jpg"/>' . "\n";

        }


        // var_dump($users);
        ?>



    </div>

</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
<script src="js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>




