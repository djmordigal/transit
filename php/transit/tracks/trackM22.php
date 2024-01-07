<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_R, $details["south_label"], "J Z");
    track_level();
    track_local(DIR_L, $details["north_label"], "J Z");
    track_platform(P_SIDE);
    track_end();
?>
