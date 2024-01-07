<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "6");
    track_platform(P_ISLAND);
    track_express_stop(DIR_B, PEAK_DIR);
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["north_label"], "6");
    track_end();
?>
