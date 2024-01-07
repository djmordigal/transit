<?php
    session_start();
    require_once("/usr/transit/data/dbviews.php");
    page_top("Home");
    bootstrap_css();
    datatables_css();
    css_include("css/common.css");
    jquery();
    bootstrap_js();
    datatables_js();
    js_include("js/index.js");
    close_head();

    $titles = array("Routes", "Stops");
    $links = array("index.php", "stops.php");
    navbar("Routes", $titles, $links);

    if(isset($_SESSION["error"])) {
        echo <<< EOD
        <div class="alert alert-danger" role="alert">
            {$_SESSION["error"]}
        </div>
EOD;
        unset($_SESSION["error"]);
    }

    $subway = get_subway_symbols();

    if($subway == false) {
        echo <<< EOD
        <div class="alert alert-danger" role="alert">
            An error occurred while fetching subway routes.
        </div>
EOD;
    }

    $bus = get_bus_symbols();

    if($bus == false) {
        echo <<< EOD
        <div class="alert alert-danger" role="alert">
            An error occurred while fetching bus routes.
        </div>
EOD;
    }
?>

<h1>Welcome</h1>
<p>Welcome to the Transit Project. This site maps out the network and
organization of the New York City subway and bus system (collectively, the
services provided by MTA New York City Transit).</p>

<p>Select a route below to view more details about it. Alternatively, check out
the <a href="stops.php"><strong>Stops</strong></a> page if you already
know the subway station or bus stop you want to see.</p>

<h2>Subway Routes</h2>
<div class="container-fluid">
<?php
    $count = 1;
    $cols_per_row = 6;
    foreach($subway as $row) {
        $id = $row["route_id"];
        $name = $row["route_long_name"];
        $img = $mtanyct_route_imgs[$id];

        if($count == 1)
            echo "<div class=\"row\">";

        echo "<div class=\"col-sm text-center\">";
        echo "<a href=\"route.php?id=$id\"><img src=\"img/$img\" alt=\"Subway route $id\" height=\"100\" width=\"100\"></a>";
        echo "<p style=\"font-size: 16pt;\">$name</p>";
        echo "</div>";

        if($count == $cols_per_row) {
            $count = 1;
            echo "</div>";
        } else
            $count++;
    }

    // close the last row if still open
    if($count > 1)
        echo "</div>";
?>
</div>

<h2>Bus Routes</h2>
<table id="tblBus" class="table table-striped table-responsive">
    <thead>
        <tr>
            <th>Route ID</th>
            <th>Route Name</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($bus as $row) {
            $id = $row["route_id"];
            $url_id = urlencode($id);

            echo "<tr>";
            echo "<td>" . get_bus_glyphs($id) . "</td>";
            echo "<td><a href=\"route.php?id=$url_id\">" . $row["route_long_name"] . "</a></td>";
            echo "</tr>";
        }
    ?>
    </tbody>
</table>

<?php
    page_bottom();
?>
