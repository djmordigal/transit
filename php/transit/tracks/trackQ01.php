<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "N Q");
    track_local(DIR_R, $details["south_label"], "N Q");
    track_platform(P_SIDE);
    track_end();
?>
