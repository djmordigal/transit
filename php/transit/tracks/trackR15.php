<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["north_label"], "N Q R W");
    track_express(DIR_L, "", "Q");
    track_express(DIR_R, "", "N Q");
    track_local(DIR_R, $details["south_label"], "N Q R W");
    track_platform(P_SIDE);
    track_end();
?>
