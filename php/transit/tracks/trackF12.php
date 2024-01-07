<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "E F FX");
    track_platform(P_SIDE);
    track_level();
    track_local(DIR_R, $details["north_label"], "E F FX");
    track_platform(P_SIDE);
    track_end();
?>
