<?php
    track_start();
    track_platform(P_SIDE, false);
    track_local(DIR_R, $details["south_label"], "2");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "2");
    track_platform(P_SIDE, false);
    track_end();
?>
