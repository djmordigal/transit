<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "M");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "M");
    track_end();
?>
