<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["south_label"], "7 7X");
    track_express_stop(DIR_B, PEAK_DIR);
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["north_label"], "7");
    track_platform(P_SIDE);
    track_end();
?>
