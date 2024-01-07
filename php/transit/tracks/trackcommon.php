<?php
    define("P_SIDE", "Side Platform");
    define("P_ISLAND", "Island Platform");
    define("DIR_L", "L");
    define("DIR_R", "R");
    define("DIR_B", "B");
    define("PEAK_DIR", "Peak direction express track");
    define("CT_UNUSED", "Center track (not used)");
    define("TRACK_UNUSED", "Track (not used)");
    define("TRACK_EMPTY", "Empty trackway");
    define("TRACK_YARD", "Yard lead track");
    define("TRACK_LAYUP", "Layup track");
    define("TRACK_PATH", "PATH track");

    function track_level() {
        echo "<div class=\"row\">";
        echo "<div class=\"level-separator\">";
            echo "<i class=\"fa-solid fa-arrow-up\"></i>"
                . "LEVEL"
                . "<i class=\"fa-solid fa-arrow-down\"></i>";
        echo "</div>";
        echo "</div>";
    }

    function track_platform($type, $used = true) {
        echo "<div class=\"row\">";
        echo "<div class=\"platform";
        if(!$used)
            echo " platform-unused";
        echo "\">$type";
        if(!$used)
            echo " (not used)";
        echo "</div></div>";
    }

    function track_unused($label) {
        echo "<div class=\"row\">";
        echo "<div class=\"border-dashed track-unused\">";
            echo "<em>$label</em>";
        echo "</div>";
        echo "</div>";
    }

    function track_local($direction, $label, $glyphs) {
        echo "<div class=\"row\">";
        switch($direction) {
            case DIR_L:
                echo "<div class=\"col-6 track-arrow track-arrow-left\">";
                    echo "<i class=\"fa-solid fa-arrow-left\"></i>";
                echo "</div>";
                echo "<div class=\"col-6 platform-sign\">";
                    echo "$label<br>" . get_subway_glyphs($glyphs, 27);
                echo "</div>";
                break;
            case DIR_R:
                echo "<div class=\"col-6 platform-sign\">";
                    echo "$label<br>" . get_subway_glyphs($glyphs, 27);
                echo "</div>";
                echo "<div class=\"col-6 track-arrow track-arrow-right\">";
                    echo "<i class=\"fa-solid fa-arrow-right\"></i>";
                echo "</div>";
                break;
            case DIR_B:
                echo "<div class=\"col-4 track-arrow track-arrow-left\">";
                    echo "<span><i class=\"fa-solid fa-arrow-left\"></i></span>";
                echo "</div>";
                echo "<div class=\"col-4 text-center text-vc\">";
                    echo "<em>";
                    if($glyphs != null)
                        echo get_subway_glyphs($glyphs) . " ";
                    echo "$label</em>";
                echo "</div>";
                echo "<div class=\"col-4 track-arrow track-arrow-right\">";
                    echo "<span><i class=\"fa-solid fa-arrow-right\"></i></span>";
                echo "</div>";
                break;
            default:
                die("Invalid call to track function");
        }
        echo "</div>";
    }

    function track_express_stop($direction, $label, $glyphs = null) {
        echo "<div class=\"row\">";
        switch($direction) {
            case DIR_L:
                if($glyphs == null)
                    die("Invalid call to track function");
                echo "<div class=\"col-6 track-arrow track-arrow-left\">";
                    echo "<span><i class=\"fa-solid fa-chevron-left\"></i>";
                    echo "<i class=\"fa-solid fa-chevron-left\"></i></span>";
                echo "</div>";
                echo "<div class=\"col-6 platform-sign\">";
                    echo "$label<br>" . get_subway_glyphs($glyphs, 27);
                echo "</div>";
                break;
            case DIR_R:
                if($glyphs == null)
                    die("Invalid call to track function");
                echo "<div class=\"col-6 platform-sign\">";
                    echo "$label<br>" . get_subway_glyphs($glyphs, 27);
                echo "</div>";
                echo "<div class=\"col-6 track-arrow track-arrow-right\">";
                    echo "<span><i class=\"fa-solid fa-chevron-right\"></i>";
                    echo "<i class=\"fa-solid fa-chevron-right\"></i></span>";
                echo "</div>";
                break;
            case DIR_B:
                echo "<div class=\"col-4 track-arrow track-arrow-left\">";
                    echo "<span><i class=\"fa-solid fa-chevron-left\"></i>";
                    echo "<i class=\"fa-solid fa-chevron-left\"></i></span>";
                echo "</div>";
                echo "<div class=\"col-4 text-center text-vc\">";
                    echo "<em>";
                    if($glyphs != null)
                        echo get_subway_glyphs($glyphs) . " ";
                    echo "$label</em>";
                echo "</div>";
                echo "<div class=\"col-4 track-arrow track-arrow-right\">";
                    echo "<span><i class=\"fa-solid fa-chevron-right\"></i>";
                    echo "<i class=\"fa-solid fa-chevron-right\"></i></span>";
                echo "</div>";
                break;
            default:
                die("Invalid call to track function");
        }
        echo "</div>";
    }

    function track_express($direction, $text, $glyphs = null) {
        echo "<div class=\"row border-dashed\">";
        switch($direction) {
            case DIR_B:
                echo "<div class=\"col-4 track-arrow track-arrow-left\">";
                    echo "<span><i class=\"fa-solid fa-chevron-left\"></i>";
                    echo "<i class=\"fa-solid fa-chevron-left\"></i></span>";
                echo "</div>";
                echo "<div class=\"col-4 text-center text-vc\">";
                    echo "<em>";
                    if($glyphs != null)
                        echo get_subway_glyphs($glyphs) . " ";
                    echo "$text</em>";
                echo "</div>";
                echo "<div class=\"col-4 track-arrow track-arrow-right\">";
                    echo "<span><i class=\"fa-solid fa-chevron-right\"></i>";
                    echo "<i class=\"fa-solid fa-chevron-right\"></i></span>";
                echo "</div>";
                break;
            case DIR_L:
                if($glyphs == null)
                    die("Invalid call to track function");
                echo "<div class=\"col-6 track-arrow track-arrow-left\">";
                    echo "<span><i class=\"fa-solid fa-chevron-left\"></i>";
                    echo "<i class=\"fa-solid fa-chevron-left\"></i></span>";
                echo "</div>";
                echo "<div class=\"col-6 text-center text-vc\">";
                    echo "<em>" . get_subway_glyphs($glyphs) .
                        "(does not stop)</em>";
                echo "</div>";
                break;
            case DIR_R:
                if($glyphs == null)
                    die("Invalid call to track function");
                echo "<div class=\"col-6 text-center text-vc\">";
                    echo "<em>" . get_subway_glyphs($glyphs) .
                        "(does not stop)</em>";
                echo "</div>";
                echo "<div class=\"col-6 track-arrow track-arrow-right\">";
                    echo "<span><i class=\"fa-solid fa-chevron-right\"></i>";
                    echo "<i class=\"fa-solid fa-chevron-right\"></i></span>";
                echo "</div>";
                break;
            default:
                die("Invalid call to track function");
        }
        echo "</div>";
    }

    function track_start() {
        echo "<div class=\"container-fluid\">";
    }

    function track_end() {
        echo "</div>";
    }
?>
