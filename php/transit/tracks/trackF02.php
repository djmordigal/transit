<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["south_label"], "F FX");
    track_unused(TRACK_UNUSED);
    track_unused(TRACK_UNUSED);
    track_local(DIR_R, $details["north_label"], "F FX");
    track_platform(P_SIDE);
    track_end();
?>
