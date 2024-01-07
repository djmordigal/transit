<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "J M Z");
    track_local(DIR_R, $details["south_label"], "J M Z");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "J M");
    track_end();
?>
