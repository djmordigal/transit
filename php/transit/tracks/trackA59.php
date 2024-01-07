<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "A");
    track_unused(TRACK_YARD);
    track_local(DIR_R, $details["south_label"], "A");
    track_platform(P_SIDE);
    track_end();
?>
