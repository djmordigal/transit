<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "B Q");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "B Q");
    track_level();
    track_local(DIR_L, $details["north_label"], "D N R");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["north_label"], "D N Q");
    track_express_stop(DIR_R, $details["south_label"], "D N");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "D N R");
    track_end();
?>
