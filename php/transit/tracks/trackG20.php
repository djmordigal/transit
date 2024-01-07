<?php
    track_start();
    track_platform(P_SIDE);
    track_local(DIR_L, $details["south_label"], "E F R");
    track_express(DIR_L, "", "E F FX");
    track_express(DIR_R, "", "E F FX");
    track_local(DIR_R, $details["north_label"], "E F R");
    track_platform(P_SIDE);
    track_end();
?>
