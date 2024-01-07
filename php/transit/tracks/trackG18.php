<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["south_label"], "E F R");
    track_local(DIR_R, $details["north_label"], "E F R");
    track_platform(P_SIDE);
    track_end();
?>
