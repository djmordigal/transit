<?php
    session_start();
    require_once("/usr/transit/data/dbviews.php");

    if(!isset($_GET["id"]))
        die_with_error();
    else if(!stop_exists($_GET["id"]))
        die_with_error();
    else
        $stop_id = $_GET["id"];

    $details = get_stop_details($stop_id);
    if($details == false)
        die_with_error();

    $ADA_FULL = "<i class=\"fa-solid fa-wheelchair access access-full\"></i>";
    $ADA_PART = "<i class=\"fa-solid fa-wheelchair access access-part\"></i>";
    $TRACK_LOCAL = "<i class=\"fa-solid fa-arrow-right\"></i>";
    $TRACK_EXPRESS = "<i class=\"fa-solid fa-chevron-right\"></i><i class=\"fa-solid fa-chevron-right\"></i>";

    if($details["route_type"] == 3)
        $subway = false;
    else
        $subway = true;

    $stop_lat = $details["stop_lat"];
    $stop_lon = $details["stop_lon"];

    page_top("Stop Detail");
    bootstrap_css();
    css_include("css/common.css");
    css_include("css/stop.css");
    jquery();
    bootstrap_js();
    fa_js();

    close_head();

    $titles = array("Routes", "Stops");
    $links = array("index.php", "stops.php");
    navbar("Stops", $titles, $links);
?>

<h1>Stop Detail</h1>

<?php
if($subway) {
    switch($details["ada"]) {
        case 1:
            $ada_text = $ADA_FULL;
            break;
        case 2:
            $ada_text = $ADA_PART;
            break;
        default:
            $ada_text = "";
            break;
    }
    echo <<< EOD
<div class="subway-box">
    <div class="subway-box-header"></div>
    <div class="subway-box-name">
        {$details["stop_name"]} {$ada_text}
    </div>
    <div class="subway-box-routes">
EOD;
    echo get_subway_glyphs($details["route_str"], 37);
    echo <<< EOD
    </div>
</div>

<h2>Legend</h2>
<table class="table table-striped table-responsive">
    <tbody>
        <tr>
            <td>{$ADA_FULL}</td>
            <td>Station is fully accessible</td>
        </tr>
        <tr>
            <td>{$ADA_PART}</td>
            <td>Station is partially accessible</td>
        </tr>
        <tr>
            <td>{$TRACK_LOCAL}</td>
            <td>Local track</td>
        </tr>
        <tr>
            <td>{$TRACK_EXPRESS}</td>
            <td>Express track</td>
        </tr>
    </tbody>
</table>

<h2>Track Layout</h2>
EOD;
require_once("tracks/trackcommon.php");
require_once("tracks/track{$stop_id}.php");
} else {
echo <<< EOD
<div class="bus-box">
<div class="bus-box-header"></div>
<div class="bus-box-name">
    {$details["stop_name"]}
</div>
<div class="bus-box-routes">
EOD;
    echo get_bus_glyphs($details["route_str"], 24);
    echo <<< EOD
    </div>
</div>
EOD;
}

$mKey = "AIzaSyAVwACFFaJwm5ZlZ353jWr_NA79R7aKttU";
$mParms = "q=$stop_lat,$stop_lon&center=$stop_lat,$stop_lon&zoom=18";

echo <<< EOD
<h2>Map</h2>
EOD;

if($subway)
    echo <<< EOD
<p><strong>Note:</strong> The map marker is placed at the center of the
platform, which may be significantly off from the station entrance that is
labeled on the map.</p>
EOD;

echo <<< EOD
<iframe src="https://www.google.com/maps/embed/v1/place?key=$mKey&$mParms"
        allowFullScreen>
</iframe>
EOD;
?>

<?php
    page_bottom();

    function die_with_error() {
        $_SESSION["error"] = "Oops! No stop specified, or the specified stop doesn't exist.";
        header("Location: stops.php");
        die();
    }
?>
