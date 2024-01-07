<?php
    track_start();
    track_platform(P_SIDE, false);
    track_local(DIR_L, $details["north_label"], "4 6 6X");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["north_label"], "4 5");
    track_express_stop(DIR_R, $details["south_label"], "4 5");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "4 6 6X");
    track_platform(P_SIDE, false);
    track_end();
?>
