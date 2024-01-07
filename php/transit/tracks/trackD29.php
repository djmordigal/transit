<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "Q");
    track_express(DIR_L, "", "B");
    track_express(DIR_R, "", "B");
    track_local(DIR_R, $details["south_label"], "Q");
    track_platform(P_SIDE);
    track_end();
?>
