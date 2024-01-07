<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "5");
    track_unused(TRACK_EMPTY);
    track_express(DIR_R, "", "5");
    track_local(DIR_R, $details["south_label"], "5");
    track_platform(P_SIDE);
    track_end();
?>
