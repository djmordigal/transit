<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "2 3 4 5");
    track_unused(TRACK_EMPTY);
    track_local(DIR_R, $details["south_label"], "2 3 4");
    track_platform(P_SIDE);
    track_end();
?>
