<?php
    track_start();
    track_platform(P_SIDE);
    track_express_stop(DIR_L, $details["north_label"], "A");
    track_express_stop(DIR_R, $details["south_label"], "A");
    track_platform(P_SIDE);
    track_level();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "A C");
    track_unused(TRACK_EMPTY);
    track_unused(TRACK_EMPTY);
    track_local(DIR_R, $details["south_label"], "A C");
    track_platform(P_SIDE);
    track_end();
?>
