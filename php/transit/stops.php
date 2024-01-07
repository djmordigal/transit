<?php
    session_start();
    require_once("/usr/transit/data/dbviews.php");

    if(isset($_POST["query"])) {
        $query = $_POST["query"];
        $stop_list = get_stops_query($query);
        if(count($stop_list) == 0) {
            $_SESSION["error"] = "Oops! No stops found.";
            header("Location: stops.php");
            die();
        }
    }

    page_top("Stops");
    bootstrap_css();
    datatables_css();
    css_include("css/common.css");
    jquery();
    bootstrap_js();
    datatables_js();
    js_include("js/stops.js");
    close_head();

    $titles = array("Routes", "Stops");
    $links = array("index.php", "stops.php");
    navbar("Stops", $titles, $links);

    if(isset($_SESSION["error"])) {
        echo <<< EOD
        <div class="alert alert-danger" role="alert">
            {$_SESSION["error"]}
        </div>
EOD;
        unset($_SESSION["error"]);
    }


?>

<h1>Stops</h1>
<?php
    if(isset($stop_list)) {
        $size = count($stop_list);
        $query_arr = str_split($query);
        $first = $query_arr[0];
        $last = $query_arr[count($query_arr) - 1];

        if(($first == "\"") && ($first == $last))
            $matchTxt = "$size "
                . (($size == 1) ? "match" : "matches")
                . " for exact name $query";
        else
            $matchTxt = "$size "
                . (($size == 1) ? "match" : "matches")
                . " for \"$query\"";
        echo <<< EOD
<a href="stops.php" class="btn btn-primary">New Search</a>
<p><em>$matchTxt</em></p>
<table id="tblStops" class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>Stop ID</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
EOD;
        for($i = 0; $i < $size; $i++) {
            if($stop_list[$i] == null)
                continue;

            $id = $stop_list[$i]["stop_id"];
            $name = $stop_list[$i]["stop_name"];
            $route1 = explode(" ", $stop_list[$i]["route_str"])[0];

            if(array_key_exists($route1, $mtanyct_route_imgs))
                $routes = get_subway_glyphs($stop_list[$i]["route_str"]);
            else
                $routes = get_bus_glyphs($stop_list[$i]["route_str"]);

            echo <<< EOD
        <tr>
            <td><a href="stop.php?id=$id">$id</a></td>
            <td><a href="stop.php?id=$id">$name</a> $routes</td>
        </tr>
EOD;
        }
        echo <<< EOD
    </tbody>
</table>
EOD;
    } else {
        echo <<< EOD
<form action="stops.php" method="post">
    <div class="form-group" style="margin-bottom: 1em;">
        <input type="text" class="form-control textbox" name="query"
            placeholder="Enter partial stop name or &quot;exact name&quot;" required>
    </div>
    <input type="submit" class="btn btn-success" value="Search">
</form>
EOD;
    }
?>

<?php
    page_bottom();
?>
