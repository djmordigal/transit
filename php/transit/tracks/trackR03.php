<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "N W");
    track_platform(P_ISLAND);
    track_express(DIR_B, PEAK_DIR);
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["north_label"], "N W");
    track_end();
?>
