<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "5");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["north_label"], "5");
    track_express_stop(DIR_R, $details["south_label"], "5");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "5");
    track_end();
?>
