<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "FS");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["north_label"], "B Q");
    track_express_stop(DIR_R, $details["south_label"], "B Q");
    track_platform(P_ISLAND);
    track_unused(TRACK_UNUSED);
    track_end();
?>
