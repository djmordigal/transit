<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "J Z");
    track_unused(get_subway_glyphs("J Z") . " termination track");
    track_platform(P_SIDE);
    track_end();
?>
