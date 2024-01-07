<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "7");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["south_label"], "7 7X");
    track_platform(P_ISLAND);
    track_express_stop(DIR_L, $details["south_label"], "7 7X");
    track_end();
?>
