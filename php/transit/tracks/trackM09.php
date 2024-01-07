<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "M");
    track_unused(TRACK_EMPTY);
    track_local(DIR_R, $details["south_label"], "M");
    track_platform(P_SIDE);
    track_end();
?>
