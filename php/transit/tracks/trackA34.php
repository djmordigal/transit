<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "E");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["north_label"], "A C");
    track_express_stop(DIR_R, $details["south_label"], "A");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "A C E");
    track_end();
?>
