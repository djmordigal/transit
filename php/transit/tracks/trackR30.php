<?php
    track_start();
    track_local(DIR_L, $details["north_label"], "B D N Q");
    track_platform(P_ISLAND);
    track_local(DIR_L, $details["north_label"], "N R W");
    track_express(DIR_L, "", "D N Q");
    track_express(DIR_R, "", "D N");
    track_local(DIR_R, $details["south_label"], "N R W");
    track_platform(P_ISLAND);
    track_local(DIR_R, $details["south_label"], "B D N Q");
    track_end();
?>
