<?php
    track_start();
    track_local(DIR_L, $details["south_label"], "Q N");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["north_label"], "F");
    track_level();
    track_local(DIR_R, $details["north_label"], "Q R");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["north_label"], "F");
    track_end();
?>
