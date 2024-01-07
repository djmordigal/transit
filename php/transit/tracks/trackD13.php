<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "B D");
    track_platform(P_ISLAND);
    track_express_stop(DIR_B, PEAK_DIR);
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "B D");
    track_end();
?>
