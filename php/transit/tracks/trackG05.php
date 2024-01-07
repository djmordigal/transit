<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "E");
    track_platform(P_ISLAND);
    track_local(DIR_L, $details["south_label"], "E");
    track_level();
    track_local(DIR_L, $details["south_label"], "J Z");
    track_platform(P_ISLAND);
    track_local(DIR_L, $details["south_label"], "J Z");
    track_end();
?>
