<?php

require("sqlConnect.php");

$reserveDate = $transactID = "";

if($_POST['status'] == "current") {
    echo "<div class='row flex-row flex-nowrap'>";
    $sql = "SELECT rooms.roomID, rooms.roomName, rooms.roomDesc, rooms.roomQty, (SELECT room_type.room_type FROM room_type WHERE rooms.roomType=room_type.idroom_type) AS roomType, (SELECT COUNT(*) FROM reservations WHERE DATE(reservationDate) = CURDATE() AND rooms.roomID=reservations.roomID) AS reserved, (SELECT placeholders.placeholder_name FROM placeholders WHERE rooms.placeholderID = placeholders.idplaceholder) AS placeholder FROM rooms,room_type WHERE rooms.roomType = room_type.idroom_type AND rooms.deleted='0';";
    $result = $mConn->query($sql);
    if($result->num_rows > 0) {
        $ctr = 0;
        while($row=$result->fetch_assoc()) {
            $reserved = 0;
            $rID = $row['roomID'];
            $sql1 = "SELECT duration FROM reservations WHERE roomID='" . $rID . "' AND DATE(reservationDate) = CURDATE();";
            $rslt1 = $mConn->query($sql1);
            if($rslt1->num_rows > 0) {
                while($row1=$rslt1->fetch_assoc()) {
                    if($row1['duration']=='24') {
                        $reserved = $reserved + 1;
                    } else {
                        $reserved = $reserved + 0.5;
                    }
                }
            }
            echo "<div class='col-sm-5'>";
            echo "<div id='donutchart-cur-$ctr' style='height: 300px; width: 100%;'></div>";
            echo "</div>";
            echo "<script type='text/javascript'>\n";
            echo "google.charts.load('current', {packages:['corechart']});\n";
            echo "google.charts.setOnLoadCallback(drawChart);\n";
            echo "function drawChart() {\n";
            echo "var data = google.visualization.arrayToDataTable([ ['Status', 'Rooms'],['Vacant', ".($row['roomQty'] - $reserved)."],['Reserved', ".$reserved."] ]);\n";
            echo "var options = { title: '" . $row['roomName'] . "', pieHole: 0.4, backgroundColor: 'transparent' };\n";
            echo "var chart = new google.visualization.PieChart(document.getElementById('donutchart-cur-$ctr'));\n";
            echo "chart.draw(data, options);\n}\n";
            echo "</script>\n";
            $ctr = $ctr+1;
        }
    }
    echo "</div>";
}

if($_POST['status'] == "tomorrow") {
    echo "<div class='row flex-row flex-nowrap'>";
    $sql = "SELECT rooms.roomID, rooms.roomName, rooms.roomDesc, rooms.roomQty, (SELECT room_type.room_type FROM room_type WHERE rooms.roomType=room_type.idroom_type) AS roomType, (SELECT COUNT(*) FROM reservations WHERE DATE(reservationDate) = (CURDATE()+INTERVAL 1 DAY) AND rooms.roomID=reservations.roomID) AS reserved, (SELECT placeholders.placeholder_name FROM placeholders WHERE rooms.placeholderID = placeholders.idplaceholder) AS placeholder FROM rooms,room_type WHERE rooms.roomType = room_type.idroom_type AND rooms.deleted='0';";
    $result = $mConn->query($sql);
    if($result->num_rows > 0) {
        $ctr = 0;
        while($row=$result->fetch_assoc()) {
            $reserved = 0;
            $rID = $row['roomID'];
            $sql1 = "SELECT duration FROM reservations WHERE roomID='" . $rID . "' AND DATE(reservationDate) = (CURDATE()+INTERVAL 1 DAY);";
            $rslt1 = $mConn->query($sql1);
            if($rslt1->num_rows > 0) {
                while($row1=$rslt1->fetch_assoc()) {
                    if($row1['duration']=='24') {
                        $reserved = $reserved + 1;
                    } else {
                        $reserved = $reserved + 0.5;
                    }
                }
            }
            echo "<div class='col-sm-5'>";
            echo "<div id='donutchart-tom-$ctr' style='height: 300px; width: 100%;'></div>";
            echo "</div>";
            echo "<script type='text/javascript'>\n";
            echo "google.charts.load('current', {packages:['corechart']});\n";
            echo "google.charts.setOnLoadCallback(drawChart);\n";
            echo "function drawChart() {\n";
            echo "var data = google.visualization.arrayToDataTable([ ['Status', 'Rooms'],['Vacant', ".($row['roomQty'] - $reserved)."],['Reserved', ".$reserved."] ]);\n";
            echo "var options = { title: '" . $row['roomName'] . "', pieHole: 0.4, backgroundColor: 'transparent' };\n";
            echo "var chart = new google.visualization.PieChart(document.getElementById('donutchart-tom-$ctr'));\n";
            echo "chart.draw(data, options);\n}\n";
            echo "</script>\n";
            $ctr = $ctr+1;
        }
    }
    echo "</div>";
    mysqli_free_result($result);
}

if($_POST['status'] == "custom") {
    echo "<div class='row'>";
    $reserveDate = cleanStr($_POST['reserveDate']);
    $sql = "SELECT rooms.roomID, rooms.roomName, rooms.roomDesc, rooms.roomQty, (SELECT room_type.room_type FROM room_type WHERE rooms.roomType=room_type.idroom_type) AS roomType, (SELECT COUNT(*) FROM reservations WHERE DATE(reservationDate) = '" . $reserveDate . "' AND rooms.roomID=reservations.roomID) AS reserved, (SELECT placeholders.placeholder_name FROM placeholders WHERE rooms.placeholderID = placeholders.idplaceholder) AS placeholder FROM rooms,room_type WHERE rooms.roomType = room_type.idroom_type AND rooms.deleted='0';";
    $result = $mConn->query($sql);
    if($result->num_rows > 0) {
        $ctr = 0;
        while($row=$result->fetch_assoc()) {
            $reserved = 0;
            $rID = $row['roomID'];
            $sql1 = "SELECT duration FROM reservations WHERE roomID='" . $rID . "' AND DATE(reservationDate)=DATE('" . $reserveDate . "');";
            $rslt1 = $mConn->query($sql1);
            if($rslt1->num_rows > 0) {
                while($row1=$rslt1->fetch_assoc()) {
                    if($row1['duration']=='24') {
                        $reserved = $reserved + 1;
                    } else {
                        $reserved = $reserved + 0.5;
                    }
                }
            }
            echo "<div class='col-sm-6'>";
            echo "<div id='donutchart-cus-$ctr' style='height: 300px; width: 100%;'></div>";
            echo "</div>";
            echo "<script type='text/javascript'>\n";
            echo "google.charts.load('current', {packages:['corechart']});\n";
            echo "google.charts.setOnLoadCallback(drawChart);\n";
            echo "function drawChart() {\n";
            echo "var data = google.visualization.arrayToDataTable([ ['Status', 'Rooms'],['Vacant', ".($row['roomQty'] - $reserved)."],['Reserved', ".$reserved."] ]);\n";
            echo "var options = { title: '" . $row['roomName'] . "', pieHole: 0.4, backgroundColor: 'transparent' };\n";
            echo "var chart = new google.visualization.PieChart(document.getElementById('donutchart-cus-$ctr'));\n";
            echo "chart.draw(data, options);\n}\n";
            echo "</script>\n";
            $ctr = $ctr+1;
        }
    }
    echo "</div>";
    echo "<div class='row'><div class='col-md'>";
    $sql = "SELECT reservations.transactionID, (SELECT rooms.roomName FROM rooms WHERE reservations.roomID = rooms.roomID) AS roomName, (SELECT rooms.deleted FROM rooms WHERE reservations.roomID = rooms.roomID) AS deleted, reservations.duration, TIME(reservationDate) AS reserveTime, (SELECT email FROM users WHERE users.idusers = reservations.userID) AS email FROM reservations WHERE DATE(reservations.reservationDate) = '" . $reserveDate . "' ORDER BY roomName ASC;";
    $result = $mConn->query($sql);
    if($result->num_rows > 0) {
        echo "<table class='table table-sm table-hover table-bordered table-stripped table-responsive-sm'>";
        echo "<thead class='thead-inverse'>";
        echo "<tr>";
        echo "<th scope='col'>Transaction ID</th>";
        echo "<th scope='col'>Room Name</th>";
        echo "<th scope='col'>Duration</th>";
        echo "<th scope='col'>Reserve Time</th>";
        echo "<th scope='col'>E-Mail</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        while($row=$result->fetch_assoc()) {
            echo "\t\t\t\t\t\t\t\t\t\t<tr>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t<th scope='row'>" . $row['transactionID'] . "</th>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t<td>" . $row['roomName'] . "</td>\n";
            echo "\t\t\t\t\t\t\t\t\t\t\t<td>" . $row['duration'] . " Hours</td>\n";
            if($row['reserveTime']=="09:00:00" && $row['duration'] != "24") {
                echo "\t\t\t\t\t\t\t\t\t\t\t<td>Morning (9AM)</td>\n";
            } elseif($row['reserveTime']=="15:00:00" && $row['duration'] != "24") {
                echo "\t\t\t\t\t\t\t\t\t\t\t<td>Afternoon (3PM)</td>\n";
            } else {
                echo "\t\t\t\t\t\t\t\t\t\t\t<td>Whole Day</td>\n";
            }
            echo "\t\t\t\t\t\t\t\t\t\t\t<td>" . $row['email'] . "</td>\n";
            echo "\t\t\t\t\t\t\t\t\t\t</tr>\n";
        }
        echo "</tbody>";
        echo "</table>";
    }
    echo "</div></div>";
}

if($_POST['status'] == "transact") {
    $transactID = cleanStr($_POST['transactId']);
    if(empty($transactID)) {
        echo "<br><h4>Transaction ID can't be empty. Please try again.</h4><br>";
    } else {
        $sql = "SELECT reservations.transactionID, reservations.reservationDate, (SELECT rooms.roomName FROM rooms WHERE reservations.roomID = rooms.roomID) AS roomName, (SELECT rooms.deleted FROM rooms WHERE reservations.roomID = rooms.roomID) AS deleted, reservations.duration, TIME(reservationDate) AS reserveTime, (SELECT email FROM users WHERE users.idusers = reservations.userID) AS email FROM reservations WHERE reservations.transactionID LIKE '%".$transactID."%'  ORDER BY reservations.transactionDate DESC;";
        $result = $mConn->query($sql);
        if($result->num_rows > 0) {
            echo "<table class='table table-sm table-hover table-bordered table-stripped table-responsive-sm'>";
            echo "<thead class='thead-inverse'>";
            echo "<tr>";
            echo "<th scope='col'>Transaction ID</th>";
            echo "<th scope='col'>Room Name</th>";
            echo "<th scope='col'>Duration</th>";
            echo "<th scope='col'>Reserve Date</th>";
            echo "<th scope='col'>Reserve Time</th>";
            echo "<th scope='col'>E-Mail</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($row=$result->fetch_assoc()) {
                $dt = new DateTime($row['reservationDate']);
                $convertedDt = $dt->format("jS M, Y");
                echo "\t\t\t\t\t\t\t\t\t\t<tr>\n";
                echo "\t\t\t\t\t\t\t\t\t\t\t<th scope='row'>" . $row['transactionID'] . "</th>\n";
                echo "\t\t\t\t\t\t\t\t\t\t\t<td>" . $row['roomName'] . "</td>\n";
                echo "\t\t\t\t\t\t\t\t\t\t\t<td>" . $row['duration'] . " Hours</td>\n";
                echo "\t\t\t\t\t\t\t\t\t\t\t<td>" . $convertedDt . "</td>\n";
                if($row['reserveTime']=="09:00:00" && $row['duration'] != "24") {
                    echo "\t\t\t\t\t\t\t\t\t\t\t<td>Morning (9AM)</td>\n";
                } elseif($row['reserveTime']=="15:00:00" && $row['duration'] != "24") {
                    echo "\t\t\t\t\t\t\t\t\t\t\t<td>Afternoon (3PM)</td>\n";
                } else {
                    echo "\t\t\t\t\t\t\t\t\t\t\t<td>Whole Day</td>\n";
                }
                echo "\t\t\t\t\t\t\t\t\t\t\t<td>" . $row['email'] . "</td>\n";
                echo "\t\t\t\t\t\t\t\t\t\t</tr>\n";
            }
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<br><h4>No results found. Please try again.</h4><br>";
        }
    }

}

function cleanStr($x) {
    $x = htmlspecialchars($x);
    $x = stripslashes($x);
    return trim($x);
}
?>
