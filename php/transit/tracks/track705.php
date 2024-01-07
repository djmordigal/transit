<?php
    track_start();
    track_express(DIR_B, PEAK_DIR);
    track_level();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["south_label"], "7");
    track_unused(TRACK_YARD);
    track_unused(TRACK_YARD);
    track_local(DIR_R, $details["north_label"], "7");
    track_platform(P_SIDE);
    track_end();
?>
