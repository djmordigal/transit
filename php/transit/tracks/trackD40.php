<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "Q");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["north_label"], "B");
    track_express_stop(DIR_R, $details["south_label"], "B");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "Q");
    track_end();
?>
