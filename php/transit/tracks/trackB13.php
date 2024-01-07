<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "D");
    track_express(DIR_B, PEAK_DIR);
    track_local(DIR_R, $details["south_label"], "D");
    track_platform(P_SIDE);
    track_end();
?>
