<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "E");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "B D");
    track_level();
    track_local(DIR_R, $details["north_label"], "E");
    track_platform(P_ISLAND);
    track_local(DIR_L, $details["north_label"], "B D");
    track_end();
?>
