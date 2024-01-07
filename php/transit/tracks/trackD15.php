<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "F FX M");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["north_label"], "B D");
    track_local(DIR_R, $details["south_label"], "F FX M");
    track_platform(P_ISLAND);
    track_express_stop(DIR_R, $details["south_label"], "B D");
    track_end();
?>
