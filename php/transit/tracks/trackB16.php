<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "D");
    track_platform(P_ISLAND);
    track_express(DIR_B, PEAK_DIR);
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "D");
    track_end();
?>
