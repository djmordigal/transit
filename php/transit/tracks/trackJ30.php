<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["south_label"], "J Z");
    track_express(DIR_B, PEAK_DIR);
    track_local(DIR_R, $details["north_label"], "J Z");
    track_platform(P_SIDE);
    track_end();
?>
