<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "F FX");
    track_platform(P_ISLAND);
    track_local(DIR_L, $details["south_label"], "F FX");
    track_unused(get_subway_glyphs("F FX") . " termination track");
    track_platform(P_ISLAND);
    track_unused(get_subway_glyphs("F FX") . " termination track");
    track_end();
?>
