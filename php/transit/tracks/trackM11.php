<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "J M");
    track_platform(P_ISLAND);
    track_express_stop(DIR_B, PEAK_DIR, "J M Z");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["north_label"], "J M");
    track_end();
?>
