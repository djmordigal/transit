<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["south_label"], "7 7X");
    track_local(DIR_R, $details["north_label"], "7 7X");
    track_platform(P_SIDE);
    track_end();
?>
