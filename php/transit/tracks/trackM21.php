<?php
    track_start();
    track_platform(P_SIDE, false);
    track_local(DIR_L, $details["north_label"], "J Z");
    track_platform(P_ISLAND);
    track_unused(TRACK_UNUSED);
    track_platform(P_ISLAND, false);
    track_unused(TRACK_UNUSED);
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "J Z");
    track_platform(P_SIDE, false);
    track_end();
?>
