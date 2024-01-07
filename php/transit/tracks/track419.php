<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "4 5");
    track_local(DIR_R, $details["south_label"], "4 5");
    track_platform(P_SIDE);
    track_end();
?>
