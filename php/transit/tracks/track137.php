<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "1");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["north_label"], "2 3");
    track_express_stop(DIR_R, $details["south_label"], "2 3");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "1");
    track_end();
?>
