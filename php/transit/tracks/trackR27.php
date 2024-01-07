<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "N R W");
    track_platform(P_ISLAND);
    track_local(DIR_B, "", "W R");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "N R W");
    track_end();
?>
