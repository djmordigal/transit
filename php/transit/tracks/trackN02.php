<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "N Q W");
    track_unused(TRACK_UNUSED);
    track_unused(TRACK_UNUSED);
    track_local(DIR_R, $details["south_label"], "N W");
    track_platform(P_SIDE);
    track_end();
?>
