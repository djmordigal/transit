<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "G");
    track_platform(P_ISLAND);
    track_unused(CT_UNUSED);
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "G");
    track_end();
?>
