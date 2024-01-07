<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "A B C");
    track_unused(TRACK_LAYUP);
    track_express(DIR_L, "", "A D");
    track_express(DIR_R, "", "A D");
    track_unused(TRACK_LAYUP);
    track_local(DIR_R, $details["south_label"], "A B C");
    track_platform(P_SIDE);
    track_end();
?>
