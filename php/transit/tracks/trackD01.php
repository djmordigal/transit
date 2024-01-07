<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "D");
    track_platform(P_ISLAND);
    track_unused(get_subway_glyphs("D") . " termination track");
    track_end();
?>
