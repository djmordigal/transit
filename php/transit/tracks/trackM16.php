<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["south_label"], "J M Z");
    track_unused(TRACK_EMPTY);
    track_local(DIR_R, $details["north_label"], "J M Z");
    track_platform(P_SIDE);
    track_end();
?>
