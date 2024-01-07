<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "F FX M");
    track_platform(P_SIDE);
    track_platform(P_SIDE);
    track_unused(TRACK_PATH);
    track_unused(TRACK_PATH);
    track_platform(P_SIDE);
    track_platform(P_SIDE);
    track_local(DIR_R, $details["south_label"], "F FX M");
    track_end();
?>
