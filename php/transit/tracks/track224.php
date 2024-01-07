<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "2 3");
    track_unused(CT_UNUSED);
    track_local(DIR_R, $details["south_label"], "2 3");
    track_platform(P_SIDE);
    track_end();
?>
