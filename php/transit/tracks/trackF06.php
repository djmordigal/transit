<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "E F FX");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["south_label"], "E");
    track_express_stop(DIR_R, $details["north_label"], "E");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["north_label"], "E F FX");
    track_end();
?>
