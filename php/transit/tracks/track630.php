<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "4 6 6X");
    track_local(DIR_R, $details["south_label"], "4 6 6X");
    track_platform(P_SIDE);
    track_end();
?>
