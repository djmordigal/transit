<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "D N R W");
    track_express(DIR_L, "", "D N");
    track_express(DIR_R, "", "D N");
    track_local(DIR_R, $details["south_label"], "D N R W");
    track_platform(P_SIDE);
    track_end();
?>
