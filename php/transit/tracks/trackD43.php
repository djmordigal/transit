<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "N");
    track_platform(P_ISLAND);
    track_local(DIR_L, $details["north_label"], "N");
    track_local(DIR_R, $details["north_label"], "Q");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["north_label"], "Q");
    track_local(DIR_R, $details["north_label"], "F FX");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["north_label"], "F FX");
    track_local(DIR_L, $details["north_label"], "D");
    track_platform(P_ISLAND);
    track_local(DIR_L, $details["north_label"], "D");
    track_end();
?>
