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

echo "{
    \"president_avgsmnt\":" . $president_avgsmnt .",
    \"speechlabelArr\": " . json_encode($speechlabelArr).",
    \"speechvalueArr\":". json_encode($speechvalueArr).",
    \"taxonomylabelArr\": " . json_encode($taxonomylabelArr).",
    \"taxonomyvalueArr\":". json_encode($taxonomyvalueArr)."
}";

$conn->close();
?>