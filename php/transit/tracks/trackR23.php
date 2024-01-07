<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "N R W");
    track_unused(TRACK_LAYUP);
    track_unused(TRACK_LAYUP);
    track_local(DIR_R, $details["south_label"], "N R W");
    track_platform(P_SIDE);
    track_end();
?>
