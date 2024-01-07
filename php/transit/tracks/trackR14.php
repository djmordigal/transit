<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "N R W");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["north_label"], "Q R");
    track_express_stop(DIR_R, $details["south_label"], "N Q");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "N R W");
    track_end();
?>
