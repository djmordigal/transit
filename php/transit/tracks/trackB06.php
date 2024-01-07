<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["south_label"], "F");
    track_local(DIR_R, $details["north_label"], "F");
    track_platform(P_SIDE);
    track_end();
?>
