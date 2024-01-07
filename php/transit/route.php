<?php
    session_start();
    require_once("/usr/transit/data/dbviews.php");

    if(!isset($_GET["id"]))
        die_with_error();
    else if(!route_exists($_GET["id"]))
        die_with_error();
    else
        $route_id = $_GET["id"];

    if(isset($_GET["dir"]))
        $direction_id = $_GET["dir"];
    else
        $direction_id = 1;

    $details = get_route_details($route_id, $direction_id);
    if($details == false)
        die_with_error();

    page_top("Route Detail");
    bootstrap_css();
    css_include("css/common.css");
    css_include("css/route.css");
    jquery();
    bootstrap_js();
    force_graph_js();
    close_head();

    $titles = array("Routes", "Stops");
    $links = array("index.php", "stops.php");
    navbar("Routes", $titles, $links);
?>
<script>
    var details = <?php echo json_encode($details); ?>;
</script>
<script src="js/route.js"></script>
<h1>Route Detail</h1>

<?php
    if($details["route"]["route_type"] == 3)
        $img = get_bus_glyphs($details["route"]["route_id"], 48);
    else
        $img = get_subway_glyphs($route_id, 100);
    echo $img;
    echo "<h2>{$details["route"]["route_long_name"]}</h2>";
    echo "<p>{$details["route"]["route_desc"]}</p>";

    $dir_label = ($direction_id == 0 ? "Northbound" : "Southbound");
    $dir_inverted = invert($direction_id);

    $url_id = urlencode($route_id);

    echo <<< EOD
    <p><strong>Current Direction (click to toggle)</strong></p>
    <p><a href="route.php?id={$url_id}&dir={$dir_inverted}" class="btn btn-primary">$dir_label</a></p>
EOD;
?>

<p><strong>How to Use the Graph</strong></p>
<p>The graph shows an aggregate view of the possible paths that a vehicle may
take, depending upon day of week, time of day, and other factors. The moving
dots indicate the general direction of travel, according to the current
direction (see above).</p>
<ul>
    <li>Click and drag any node to reposition the graph (this does not change
        the connection order of the nodes).</li>
    <li>Hover over a node to view the name of the stop it represents. If
        transfers are available, they appear under the stop name. (Note that not
        all transfers may be available at all times.)</li>
    <li>Click a node to navigate to its stop detail page.</li>
</ul>

<div class="spinner"></div>
<div id="myGraph"></div>

<?php
    page_bottom();

    function invert($arg) {
        if($arg == 1)
            return 0;
        else
            return 1;
    }

    function die_with_error() {
        $_SESSION["error"] = "Oops! No route specified, or the specified route doesn't exist.";
        header("Location: index.php");
        die();
    }
?>
