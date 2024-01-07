<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "1 2");
    track_express(DIR_L, "", "2 3");
    track_express(DIR_R, "", "2 3");
    track_local(DIR_R, $details["south_label"], "1 2");
    track_platform(P_SIDE);
    track_end();
?>
