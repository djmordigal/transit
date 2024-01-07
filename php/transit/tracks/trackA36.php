<?php
    track_start();
    track_express_stop(DIR_L, $details["north_label"], "A C");
    track_platform(P_ISLAND);
    track_express_stop(DIR_R, $details["south_label"], "A C");
    track_end();
?>
