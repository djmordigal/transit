<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["south_label"], "SI");
    track_local(DIR_R, $details["north_label"], "SI");
    track_platform(P_SIDE);
    track_end();
?>
