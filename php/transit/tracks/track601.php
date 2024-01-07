<?php
    track_start();
    track_platform(P_SIDE, false);
    track_local(DIR_L, $details["south_label"], "6 6X");
    track_platform(P_ISLAND);
    track_local(DIR_L, $details["south_label"], "6 6X");
    track_platform(P_SIDE, false);
    track_end();
?>
