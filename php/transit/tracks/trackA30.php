<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "A C E");
    track_express(DIR_L, "", "A");
    track_express(DIR_R, "", "A");
    track_local(DIR_R, $details["south_label"], "A C E");
    track_platform(P_SIDE);
    track_end();
?>
