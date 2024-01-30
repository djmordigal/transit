<?php
    /**
     * Generate the top of the HTML document.
     *
     * $title - the page title
     */
    function page_top($title) {
        echo <<< EOD
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
    <head>
        <title>$title - Transit Project</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
EOD;
    }

    /**
     * Generate the navbar.
     *
     * $active - the name of the active page
     * $titles - array of page titles
     * $links - array of page links, relative to the current page
     */
    function navbar($active, $titles = array(), $links = array()) {
        if(count($links) != count($titles))
            die("Error: invalid call to navbar function");

        echo <<< EOD
        <nav class="navbar navbar-expand-lg bg-body-tertiary" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Transit Project</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
EOD;
        for($i = 0; $i < count($links); $i++) {
            $link = $links[$i];
            $title = $titles[$i];
            if($active == $title) {
                $actStr = " active";
                $ariaCurr = "aria-current=\"page\"";
            } else {
                $actStr = "";
                $ariaCurr = "";
            }

            echo "<li class=\"nav-item\">";
                echo "<a class=\"nav-link{$actStr}\" $ariaCurr href=\"$link\">$title</a>";
            echo "</li>";
        }
        echo <<< EOD
                    </ul>
                </div>
            </div>
        </nav>
EOD;
    }

    /**
     * Include the Bootstrap CSS style sheet.
     */
    function bootstrap_css() {
        echo <<< EOD
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9"
            crossorigin="anonymous">
EOD;
    }

    /**
     * Include the DataTables CSS style sheet.
     */
    function datatables_css() {
        echo <<< EOD
        <link rel="stylesheet"
            href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"
            type="text/css">
EOD;
    }

    /**
     * Include the Bootstrap JavaScript.
     */
    function bootstrap_js() {
        echo <<< EOD
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
            crossorigin="anonymous"></script>
EOD;
    }

    /**
     * Include the jQuery JavaScript.
     */
    function jquery() {
        echo <<< EOD
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
            crossorigin="anonymous"></script>
EOD;
    }

    /**
     * Include the Force Graph JavaScript.
     */
    function force_graph_js() {
        echo <<< EOD
        <script src="https://unpkg.com/force-graph"></script>
EOD;
    }

    /**
     * Include the DataTables JavaScript.
     */
    function datatables_js() {
        echo <<< EOD
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
EOD;
    }

    /**
     * Include the Font Awesome JavaScript.
     */
    function fa_js() {
        echo <<< EOD
        <script src="https://kit.fontawesome.com/35f8a1f806.js" crossorigin="anonymous"></script>
EOD;
    }

    /**
     * Include a custom (local) JavaScript file.
     *
     * $file - usually a relative path to the include file
     */
    function js_include($file) {
        echo <<< EOD
        <script src="$file"></script>
EOD;
    }

    /**
     * Include a custom (local) CSS file.
     *
     * $file - usually a relative path to the include file
     */
    function css_include($file) {
        echo <<< EOD
        <link rel="stylesheet" type="text/css" href="$file">
EOD;
    }

    /**
     * Close the HTML DOM head and start the body.
     */
    function close_head() {
        echo <<< EOD
    </head>
    <body>
EOD;
    }

    /**
     * Close the HTML DOM body and the rest of the document.
     */
    function page_bottom() {
        echo <<< EOD
    <footer>
        Subway Route Symbols &reg; Metropolitan Transportation Authority. Used with permission.<br>
	Designed &amp; Developed by <a href="https://davidmordigal.com/" style="text-decoration: underline;" target="_blank">David Mordigal</a> |
	Powered by <a href="https://docker.com" style="text-decoration: underline;" target="_blank">Docker</a> |
	<a href="https://github.com/djmordigal/transit" style="text-decoration: underline;" target="_blank">View the source code</a>
    </footer>
    </body>
</html>
EOD;
    }

    // MTA NYC Subway route logos
    // associative array: route ID => image file name
    $mtanyct_route_imgs = array(
        "1"  => "t1bullet.svg",
        "2"  => "t2bullet.svg",
        "3"  => "t3bullet.svg",
        "4"  => "t4bullet.svg",
        "5"  => "t5bullet.svg",
        "6"  => "t6bullet.svg",
        "6X" => "t6xbullet.svg",
        "7"  => "t7bullet.svg",
        "7X" => "t7xbullet.svg",
        "A"  => "tabullet.svg",
        "B"  => "tbbullet.svg",
        "C"  => "tcbullet.svg",
        "D"  => "tdbullet.svg",
        "E"  => "tebullet.svg",
        "F"  => "tfbullet.svg",
        "FS" => "tsbullet.svg",
        "FX" => "tfxbullet.svg",
        "G"  => "tgbullet.svg",
        "GS" => "tsbullet.svg",
        "H"  => "tsbullet.svg",
        "J"  => "tjbullet.svg",
        "L"  => "tlbullet.svg",
        "M"  => "tmbullet.svg",
        "N"  => "tnbullet.svg",
        "Q"  => "tqbullet.svg",
        "R"  => "trbullet.svg",
        "SI" => "sirbullet.svg",
        "W"  => "twbullet.svg",
        "Z"  => "tzbullet.svg"
    );

    // NYC borough names
    // associative array: abbreviation => full name
    $mtanyct_borough_names = array(
        "Bk" => "Brooklyn",
        "Bx" => "The Bronx",
        "M" => "Manhattan",
        "Q" => "Queens",
        "SI" => "Staten Island"
    );

    // bus routes - for more convenient reference
    $mtanyct_bus_routes = get_bus_routes();

    /**
     * Returns a string of subway route logos in place of the specified string
     * of route IDs. For example, if the input were "A C E", the returned value
     * would be a string of <img> tags for each of the A, C, and E route logos.
     *
     * $input - a string of route IDs
     * $size - the image size (value for height and width in the <img> tag)
     */
    function get_subway_glyphs($input, $size = 30) {
        global $mtanyct_route_imgs;
        $routes = explode(" ", $input);
        $text = "";
        $cnt = count($routes);

        for($i = 0; $i < $cnt; $i++) {
            $id = urlencode($routes[$i]);
            $path = $mtanyct_route_imgs[$routes[$i]];
            $text .= "<a href=\"route.php?id=$id\"><img src=\"img/$path\" "
                . "alt=\"Subway route $id\" height=\"$size\" width=\"$size\"></a> ";
        }

        return $text;
    }

    /**
     * Similar to get_subway_glyphs above, except it creates a box that is
     * styled instead of returning an <img> tag.
     *
     * $input - a string of route IDs
     * $size - the font size for the returned "image"
     */
    function get_bus_glyphs($input, $size = 14) {
        global $mtanyct_bus_routes;
        $routes = explode(" ", $input);
        $text = "";
        $cnt = count($routes);

        for($i = 0; $i < $cnt; $i++) {
            $id = $routes[$i];
            $url_id = urlencode($id);
            $rt = $mtanyct_bus_routes[$id];
            $bg = $rt["route_color"];
            $fg = $rt["route_text_color"];
            $style = "font-weight: bold; "
                . "font-size: {$size}pt; "
                . "background: #$bg; "
                . "color: #$fg; "
                . "padding-left: 0.2em; "
                . "padding-right: 0.2em;";
            $text .= "<a href=\"route.php?id=$url_id\" style=\"$style\">$id</a>";

            if($i < $cnt - 1)
                $text .= " ";
        }

        return $text;
    }
?>
