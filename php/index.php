<?php
$servername = "localhost:3306";
$username = "DB USERNAME";
$password = "DB PASSWORD";
$dbname = "DATABASE";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if(isset($_POST['presname']))
{
    $data['presname'] = $_POST['presname'];
}
else
{
    $data['presname'] = 'George Washington';
}

// Drop Down Presidents Names
$dropdows_sql = "select distinct President from presidents";
$dropdows_result = $conn->query($dropdows_sql);


// Presidents Average Sentiment
$pres_avgsmnt_sql = "select President, round(avg(Sentiment),2) as avg_sentiment from presidents 
where President='" . $data['presname']. "'
group by President
order by    President asc";
$pres_avgsmnt_result = $conn->query($pres_avgsmnt_sql);

$president_name = '';
$president_avgsmnt = '';

if ($pres_avgsmnt_result->num_rows > 0) {
    // output data of each row
    while($row = $pres_avgsmnt_result->fetch_assoc()) {
        $president_name = $row["President"];
        $president_avgsmnt = $row["avg_sentiment"];
    }
} else {
    echo "0 results";
}

// Presidents Taxonomy
$pres_taxonomy_sql = "select Taxonomy from presidents where President='". $data['presname']. "'";
$pres_taxonomy_result = $conn->query($pres_taxonomy_sql);

$taxonomyArray = array();
$taxonomylabelArr = array();
$taxonomyvalueArr = array();

if ($pres_taxonomy_result->num_rows > 0) {
    // output data of each row
    while($row = $pres_taxonomy_result->fetch_assoc()) {
        foreach (explode(',', $row["Taxonomy"]) as $value)
            //print $value;
            $taxonomyArray[] = $value;
    }

    // Process with array_count_values
    $taxonomyArray = array_count_values($taxonomyArray);

    foreach($taxonomyArray as $key => $value)
    {
        array_push($taxonomylabelArr, $key);
        array_push($taxonomyvalueArr, $value);
    };
} else {
    echo "0 results";
}

// Presidents Speeches
$pres_speech_sql = "select `Date`, Sentiment from presidents where President='". $data['presname']. "'";
$pres_speech_result = $conn->query($pres_speech_sql);

$speechArray = array();
$speechlabelArr = array();
$speechvalueArr = array();

if ($pres_speech_result->num_rows > 0) {
    // output data of each row
    while($row = $pres_speech_result->fetch_assoc()) {
        $speechlabelArr[] = $row["Date"];
        $speechvalueArr[] = $row["Sentiment"];
    }
} else {
    echo "0 results";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <title>US Presidents expert.ai Speech Sentiment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="US Presidents expert.ai Speech Sentiment" />
    <meta name="author" content="Vasilis Pantazopoulos">
    <meta charset="UTF-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
	<link rel="icon" type="image/ico" href="images/favicon.ico" />


    <link href="css/style.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x"
          crossorigin="anonymous">

</head>

<body data-spy="scroll" data-target=".navbar" data-offset="25">


	<!-- Preloader -->
	<div class="mask"><div id="loader"></div></div>
	<!--/Preloader -->
    
    
    
    
    
	<!-- Home Section -->
	<div id="home">

        	<img id="cycle-loader" src="images/ajax-loader.gif" />
			<div id="fullscreen-slider">
				<div>
                    <img src="images/fs1.jpeg" alt="" />
                    <div class="pattern">
                        <div class="slide-content light">
                            <div class="div-align-center">              
                                <h1>Welcome to Sentilyzer</h1>
                                <a href="#information" class="newave-button medium grey">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <img src="images/fs1.jpeg" alt="" />
                    <div class="pattern">
                        <div class="slide-content light">
                            <div class="div-align-center">
                                <h1>Expert.ai Framework</h1>
                                <a href="#information" class="newave-button medium grey">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        
	</div>	
	<!-- End Home Section -->

    <!-- Navigation -->
<!--    <nav id="navigation" class="navbar navbar-fixed-top navbar-expand-lg navbar-light bg-light">-->
<!--        <div class=".container-fluid">-->
<!--        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">-->
<!--            <span class="navbar-toggler-icon"></span>-->
<!--        </button>-->
<!--        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">-->
<!--            <div class="navbar-nav">-->
<!--                <a class="nav-item nav-link active" href="#home">Home</a>-->
<!--                <a class="nav-item nav-link" href="#information">Information</a>-->
<!--                <a class="nav-item nav-link" href="#presidents">Presidents</a>-->
<!--            </div>-->
<!--        </div>-->
<!--        </div>-->
<!--    </nav>-->
    <!-- End Navigation -->


	<!-- Information-->
	<section id="information" class="content">
    	
        <!-- Container -->
		<div class="container">
			
            <!-- Section Title -->
            <div class="section-title">
                <h1>Information</h1>
                <span class="border"></span>
            </div>				
			<!--/Section Title -->

            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="h-200 py-5 text-white bg-info rounded-3">
                    <p class="fs-4">Welcome to Sentilyzer, a sentiment visualization tool of the United States President Speeches.</p>
                    <p class="fs-4">Sentilyzer was created in the context of Sentiment & Opinion Mining Natural Language API Hackathon</p>
                    <p class="fs-4">organized by <a href="https://devpost.com" target="_blank" class="newave-button medium btn-light">Devpost</a></p>
                </div>
            </div>


            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card p-3 mb-2" style="width: 18rem;">
                            <img src="images/president.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Presidents</h5>
                                <p class="card-text">44 Presidents</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3 mb-2" style="width: 18rem;">
                            <img src="images/speech.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Speeches</h5>
                                <p class="card-text">991 Speeches</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3 mb-2" style="width: 18rem;">
                            <img src="images/analysis.png" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Analysis</h5>
                                <p class="card-text">Expert.ai Framework Used</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

		</div>
        <!--/Container -->
        
	</section>	
	<!--/Information-->

    <!-- Presidents-->
    <section id="presidents" class="presidents" name="presidents" class="content">

        <!-- Container -->
        <div class="container">

            <!-- Section Title -->
            <div class="section-title">
                <h1>Presidents</h1>
                <span class="border"></span>
                <p>Select a President from the list to view Speech Sentiment Analysis.</p>
            </div>
            <!--/Section Title -->


                <div class="row my-3">
                    <div class="col">
                        <div>
                            <form action="index.php" method="POST">
<!--                                <h3 class="card-title">President</h3>-->
                                <div class="select">
                                    <?php
                                    echo "<select name=\"presname\" id=\"presname\">";
                                    echo "<option size =30 ></option>";
                                    while($row = $dropdows_result->fetch_assoc()) {
                                        echo "<option value='" . $row['President'] . "'>" . $row['President'] . "</option>";
                                    }
                                    echo "</select>";
                                    ?>
                                </div>
                                <input id="btnSubmit" name="btnSubmit" type="button" class="hide-submit" value="Submit" />
                            </form>
                        </div>
                        <!--                <h4>--><?php //echo "President: ". $president_name?><!--</h4>-->
                        <h4>Average Speeches Sentiment: <span id="spanavgsmnt"><?php echo $president_avgsmnt?></span></h4>
                    </div>
                </div>
                <div class="row my-2">
                    <div class="col-md-6 py-1">
                        <div class="card">
                            <div class="card-body">
                                <div class="panel-heading ">
                                    <h5 class="card-title">President Speeches Overall Emotions</h5>
                                </div>
                                <canvas id="taxonomyChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 py-1">
                        <div class="card">
                            <div class="card-body">
                                <div class="panel-heading ">
                                    <h5 class="card-title">President Speeches Sentiment</h5>
                                </div>
                                <canvas id="speechChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
        <!--/Container -->

    </section>
    <!--/Presidents-->

    <!-- Footer -->
    <footer>
		<div class="container no-padding">
        	
            <a id="back-top"><div id="menu_top"><div id="menu_top_inside"></div></div></a>
            
<!--            <ul class="socials-icons">-->
<!--                <li><a href="#"><img src="images/facebook.png" alt="" /></a></li>-->
<!--                <li><a href="#"><img src="images/twitter.png" alt="" /></a></li>-->
<!--            </ul>-->
            
			<p class="copyright">2021 &copy; Sentilyzer. All rights reserved.</p>
            
		</div>
	</footer>
	<!--/Footer -->
  
    
    
	
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js'></script>
    <script src="js/jquery.sticky.js"></script>
	<script src="js/jquery.easing-1.3.pack.js" type="text/javascript"></script>
<!--	<script src="js/bootstrap.js" type="text/javascript"></script>-->
	<script src="js/appear.js" type="text/javascript" ></script>
	<script src="js/modernizr.js" type="text/javascript"></script>
	<script src="js/jquery.prettyPhoto.js" type="text/javascript"></script>
    <script src="js/isotope.js" type="text/javascript"></script>
    <script src="js/jquery.bxslider.min.js"></script>
    <script src="js/jquery.cycle.all.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery.maximage.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/scripts.js" type="text/javascript"></script>
<!---->
<!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.3.2/dist/chart.min.js"></script>
    <!--    taxonomy chart script-->
    <script>
        const dataTaxonomy = {
            labels: <?php echo json_encode($taxonomylabelArr) ?>,
            datasets: [{
                label: 'Emotion',
                data: <?php echo json_encode($taxonomyvalueArr); ?>,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)',
                    'rgb(255, 205, 86)',
                    'rgb(201, 203, 207)',
                    'rgb(54, 162, 235)'
                ]
            }]
        };

        const configTaxonomy = {
            type: 'bar',
            data: dataTaxonomy,
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        var myChartTaxonomy = new Chart(
            document.getElementById('taxonomyChart'),
            configTaxonomy
        );
    </script>

    <!--    speech chart script-->
    <script>
        const dataSpeech = {
            labels: <?php echo json_encode($speechlabelArr) ?>,
            datasets: [{
                label: 'Speech',
                data: <?php echo json_encode($speechvalueArr); ?>,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)',
                    'rgb(255, 205, 86)',
                    'rgb(201, 203, 207)',
                    'rgb(54, 162, 235)'
                ]
            }]
        };

        const configSpeech = {
            type: 'line',
            data: dataSpeech,
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        var myChartSpeech = new Chart(
            document.getElementById('speechChart'),
            configSpeech
        );
    </script>
    <script>
        $(document).ready(()=>{
            $("#presname").val("<?php echo $data['presname'] ?>");
        });
    </script>
    <!--    ajax call-->
    <script>
        $(document).ready(function() {
            $("#presname").change(function() {
                var presname = $("#presname").val();
                if(presname=='') {
                    alert("Please select president.");
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "scratch.php",
                    data: {
                        presname: presname
                    },
                    cache: false,
                    success: function(result) {
                        var json = $.parseJSON(result);
                        $('#spanavgsmnt').text(json.president_avgsmnt);
                        dataSpeech.labels = json.speechlabelArr;
                        dataSpeech.datasets[0].data = json.speechvalueArr;
                        myChartSpeech.update();
                        dataTaxonomy.labels = json.taxonomylabelArr;
                        dataTaxonomy.datasets[0].data = json.taxonomyvalueArr;
                        myChartTaxonomy.update();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr);
                    }
                });
            });
        });
    </script>
    <!--    bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
            crossorigin="anonymous"></script>

	</body>
</html>