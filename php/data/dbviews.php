<?php
    require_once("dbconnection.php");
    require_once("common/common.php");

    /**
     * Retrieves a list of all route IDs and names for which there is at least
     * one trip, sorted by what is essentially the most "logical" order: in
     * alphanumeric order, grouped by route color.
     *
     * This returned set is used by the front page to display all of the subway
     * routes in a grid.
     */
    function get_subway_symbols() {
        $c = get_connection();
        if($c == false)
            return false;

        $sql = "SELECT route_id, route_long_name "
            . "FROM routes "
            . "JOIN trips USING (route_id) "
            . "WHERE route_type < 3 "
            . "GROUP BY (trips.route_id) "
            . "HAVING COUNT(trips.route_id) > 0 "
            . "ORDER BY FIELD(route_short_name, '1', '2', '3', '4', '5', '6', "
            . "'6X', '7', '7X', 'A', 'C', 'E', 'B', 'D', 'F', 'FX', 'M', 'G', "
            . "'J', 'Z', 'L', 'N', 'Q', 'R', 'W', 'S', 'SIR')";
        $stmt = $c->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rs;
    }

    /**
     * Similar to get_subway_symbols, except for buses, and the set is simply
     * returned in order of route ID. Since there are so many bus routes, the
     * results are formatted into a table instead of a grid.
     */
    function get_bus_symbols() {
        $c = get_connection();
        if($c == false)
            return false;

        $sql = "SELECT route_id, route_long_name, route_color, route_text_color "
            . "FROM routes "
            . "JOIN trips USING (route_id) "
            . "WHERE route_type = 3 "
            . "GROUP BY (trips.route_id) "
            . "HAVING COUNT(trips.route_id) > 0 "
            . "ORDER BY routes.route_id";
        $stmt = $c->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rs;
    }

    /**
     * Checks whether a route with the specified ID exists.
     *
     * returns: 1 if it exists, 0 otherwise
     */
    function route_exists($route_id) {
        $c = get_connection();
        if($c == false)
            return false;

        $sql = "SELECT "
            . "EXISTS(SELECT route_id FROM routes WHERE route_id = :id) "
            . "AS result";
        $stmt = $c->prepare($sql);
        $stmt->bindValue(":id", $route_id);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ($rs[0]["result"]);
    }

    /**
     * Checks whether a stop with the specified ID exists.
     *
     * returns: 1 if it exists, 0 otherwise
     */
    function stop_exists($stop_id) {
        $c = get_connection();
        if($c == false)
            return false;

        $sql = "SELECT "
            . "EXISTS(SELECT stop_id FROM stops WHERE stop_id = :id) "
            . "AS result";
        $stmt = $c->prepare($sql);
        $stmt->bindValue(":id", $stop_id);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ($rs[0]["result"]);
    }

    /**
     * Returns a list of stops (subway and bus) that match the specified query.
     * The query can be a partial stop name (not enclosed in quotes) or an exact
     * name (enclosed in quotes).
     */
    function get_stops_query($query) {
        // remove all percent signs, we add those ourselves
        $q = str_replace("%", "", $query);

        // strip leading/trailing whitespace
        $q = trim($q);

        // if the query was empty, just reject it
        if(strlen($q) == 0)
            return array();

        $c = get_connection();
        if($c == false)
            return array();

        $q_arr = str_split($q);             // split query into characters
        $first = $q_arr[0];                 // get first character
        $last = $q_arr[count($q_arr) - 1];  // get last character

        // if a quote ('' or "") was specified as the first and last character,
        // user wants an exact search (exact name)
        if(($first == "\"" || $first == "'") && ($first == $last)) {
            $exact = true;

            // we know it's exact now, remove the quotes
            $q = str_replace("\"", "", $q);
            $q = str_replace("'", "", $q);

            // wait - still empty?
            if(strlen($q) == 0)         // whoops, shouldn't be here
                return array();

            $sql = "SELECT IFNULL(stops.parent_station, stops.stop_id) "
                . "AS stop_id, stops.stop_name, "
                . "stop_routes.route_string AS route_str "
                . "FROM stops "
                . "JOIN stop_routes USING (stop_id) "
                . "WHERE stops.stop_name = :query "
                . "ORDER BY stops.stop_id";
        } else {
            // otherwise, user wants a "like" search (partial name)
            $sql = "SELECT IFNULL(stops.parent_station, stops.stop_id) "
                . "AS stop_id, stops.stop_name, "
                . "stop_routes.route_string AS route_str "
                . "FROM stops "
                . "JOIN stop_routes USING (stop_id) "
                . "WHERE stops.stop_name LIKE :query "
                . "ORDER BY stops.stop_id";
            $exact = false;
        }
        $stmt = $c->prepare($sql);

        if($exact)
            $stmt->bindValue(":query", "$q");       // exact, as-is
        else
            $stmt->bindValue(":query", "%{$q}%");   // partial, "like"
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $size = count($rs);

        $final_rs = array();
        $dupe = false;

        for($i = 0; $i < $size; $i++) {
            $name = $rs[$i]["stop_name"];
            if($name == strtoupper($name))
                $rs[$i]["stop_name"] = title_case($name);

            if($i == 0) {
                array_push($final_rs, $rs[$i]);
                $row_save = $rs[$i];
                $last_row = $rs[$i];
                continue;
            }

            $last = count($final_rs) - 1;
            if($rs[$i]["stop_id"] == $final_rs[$last]["stop_id"]) {
                $final_rs[$last]["route_str"] .= " " . $rs[$i]["route_str"];

                if($i == $size - 1)
                    $final_rs[$last]["route_str"] =
                        remove_dupes($final_rs[$last]["route_str"]);
                else
                    $dupe = true;
            } else {
                if($dupe) {
                    $final_rs[$last]["route_str"] =
                        remove_dupes($final_rs[$last]["route_str"]);
                    $dupe = false;
                }
                array_push($final_rs, $rs[$i]);
            }
        }

        return $final_rs;
    }

    /**
     * Remove duplicate route IDs from a route string (basically used in stop
     * views - search results and individual detail pages).
     */
    function remove_dupes($input) {
        $route_arr = explode(" ", $input);
        $route_arr = array_unique($route_arr);
        sort($route_arr);
        return implode(" ", $route_arr);
    }

    /**
     * Returns an associative array of "everything you need to know" about a
     * given stop. If the stop is a subway stop, also retrieves data from the
     * supplemental "stations" table, an extra CSV file that MTA provides
     * separately from the GTFS feeds.
     */
    function get_stop_details($stop_id) {
        $c = get_connection();
        if($c == false)
            return false;

        $sql = "SELECT stops.stop_name, stops.stop_lat, stops.stop_lon, "
            . "routes.route_type, "
            . "stations.north_label, stations.south_label, stations.ada, "
            . "stop_routes.route_string AS route_str "
        . "FROM stops "
        . "LEFT JOIN stations ON (stops.parent_station = stations.gtfs_stop_id) "
        . "JOIN stop_routes USING (stop_id) "
        . "JOIN stop_times USING (stop_id) "
        . "JOIN trips USING (trip_id) "
        . "JOIN routes USING (route_id) "
        . "WHERE IFNULL(stops.parent_station, stops.stop_id) = :id";
        $stmt = $c->prepare($sql);
        $stmt->bindValue(":id", $stop_id);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $name = $rs[0]["stop_name"];

        // if name is all uppercase, convert it to title case
        if($name == strtoupper($name))
            $rs[0]["stop_name"] = title_case($name);

        // if this was a subway route, more than one result will probably be
        // returned - in this case, combine the "route string" information
        $size = count($rs);
        if($size > 1) {
            $route_bld = "";
            for($i = 0; $i < $size; $i++)
                $route_bld .= $rs[$i]["route_str"] . " ";

            $route_bld = remove_dupes(rtrim($route_bld));
            $rs[0]["route_str"] = $route_bld;
        }

        return $rs[0];
    }

    /**
     * Returns an associative array of "everything you need to know" about a
     * given route, for the given direction of travel. This includes a list of
     * every stop ID of every trip (in route order), any available transfers
     * (for subway routes), in addition to the usual route details.
     */
    function get_route_details($route_id, $direction_id) {
        global $mtanyct_route_imgs;
        $c = get_connection();
        if($c == false)
            return false;

        // step 1: route details
        $sql = "SELECT route_id, route_short_name, route_long_name, "
            . "route_desc, route_color, route_text_color, route_type "
            . "FROM routes "
            . "WHERE route_id = :id";
        $stmt = $c->prepare($sql);
        $stmt->bindValue(":id", $route_id);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $type = $rs[0]["route_type"];
        $result = array();
        $result["route"] = $rs[0];

        // step 2: trip information
        $sql = "SELECT trip_id FROM trips "
            . "WHERE route_id = :id "
            . "AND direction_id = :dir";
        $stmt = $c->prepare($sql);
        $stmt->bindValue(":id", $route_id);
        $stmt->bindValue(":dir", $direction_id);
        $stmt->execute();
        $trip_ids = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result["trips"] = array();   // array of arrays, stop IDs for each trip
        $result["stops"] = array();   // array of unique stop IDs => details
        foreach($trip_ids as $row) {
            $trip_id = $row["trip_id"];

            // step 3: stop details
            if($type == 3)
                // if the route type is a bus route, get information for the
                // stop record itself
                $sql = "SELECT stop_id, stop_name, stop_lat, stop_lon "
                . "FROM stops "
                . "JOIN stop_times USING (stop_id) "
                . "JOIN trips USING (trip_id) "
                . "WHERE trips.trip_id = :trip "
                . "ORDER BY stop_times.stop_sequence";
            else
                // otherwise, subway stops along a trip are actually "child"
                // stops of a parent station, so we get the info for the parent
                // station instead to avoid duplicate information
                $sql = "SELECT parent_station, stop_name, stop_lat, stop_lon, "
                    . "stations.borough, stations.ada, "
                    . "stations.ada_direction_notes, "
                    . "stations.ada_nb, stations.ada_sb "
                    . "FROM stops "
                    . "JOIN stations ON (stops.parent_station = stations.gtfs_stop_id) "
                    . "JOIN stop_times USING (stop_id) "
                    . "JOIN trips USING (trip_id) "
                    . "WHERE trips.trip_id = :trip "
                    . "ORDER BY stop_times.stop_sequence";
            $stmt = $c->prepare($sql);
            $stmt->bindValue(":trip", $trip_id);
            $stmt->execute();
            $stop_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this_trip = array();
            foreach($stop_list as $stop) {
                if($type == 3)
                    $stop_id = $stop["stop_id"];
                else
                    $stop_id = $stop["parent_station"];
                array_push($this_trip, $stop_id);

                if(array_key_exists($stop_id, $result["stops"]))
                    continue;

                // step 4: any available transfers and extra station info,
                // for subway only
                if($type != 3) {
                    $sql = "SELECT GROUP_CONCAT(DISTINCT routes.route_id "
                        . "ORDER BY routes.route_id SEPARATOR ' ') "
                        . "AS to_route_id "
                        . "FROM transfers "
                        . "JOIN stops ON (stops.parent_station = transfers.to_stop_id) "
                        . "JOIN stop_times USING (stop_id) "
                        . "JOIN trips USING (trip_id) "
                        . "JOIN routes USING (route_id) "
                        . "WHERE from_stop_id = :from_stop_id "
                        . "AND routes.route_id != :route_id "
                        . "GROUP BY transfers.from_stop_id";
                    $stmt = $c->prepare($sql);
                    $stmt->bindValue(":from_stop_id", $stop_id);
                    $stmt->bindValue(":route_id", $route_id);
                    $stmt->execute();
                    $xfers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $borough = $stop["borough"];
                    $ada = $stop["ada"];
                    $ada_direction_notes = $stop["ada_direction_notes"];
                    $ada_nb = $stop["ada_nb"];
                    $ada_sb = $stop["ada_sb"];
                    $result["stops"][$stop_id]["borough"] = $borough;
                    $result["stops"][$stop_id]["ada"] = $ada;
                    $result["stops"][$stop_id]["ada_direction_notes"]
                        = $ada_direction_notes;
                    $result["stops"][$stop_id]["ada_nb"] = $ada_nb;
                    $result["stops"][$stop_id]["ada_sb"] = $ada_sb;

                    if(count($xfers) == 1)
                        $result["stops"][$stop_id]["transfers"]
                            = $xfers[0]["to_route_id"];
                }
                $stop_name = $stop["stop_name"];
                if($stop_name == strtoupper($stop_name))
                    $stop_name = title_case($stop_name);
                $stop_lat = $stop["stop_lat"];
                $stop_lon = $stop["stop_lon"];
                $result["stops"][$stop_id]["name"] = $stop_name;
                $result["stops"][$stop_id]["latitude"] = $stop_lat;
                $result["stops"][$stop_id]["longitude"] = $stop_lon;
            }
            array_push($result["trips"], $this_trip);
        }

        // step 5: route symbols
        $result["images"] = $mtanyct_route_imgs;
        return $result;
    }

    function get_bus_routes() {
        $c = get_connection();
        if($c == false)
            return false;

        $sql = "SELECT * FROM routes "
            . "WHERE route_type = 3";
        $stmt = $c->prepare($sql);
        $stmt->execute();
        $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = array();
        $size = count($rs);
        foreach($rs as $row) {
            $key = $row["route_id"];
            $val = array(
                "route_short_name" => $row["route_short_name"],
                "route_long_name" => $row["route_long_name"],
                "route_desc" => $row["route_desc"],
                "route_type" => $row["route_type"],
                "route_url" => $row["route_url"],
                "route_color" => $row["route_color"],
                "route_text_color" => $row["route_text_color"]
            );
            $result[$key] = $val;
        }

        return $result;
    }

    /**
     * Convert the specified string to title case (uppercase the first letter of
     * each word).
     */
    function title_case($text) {
        return ucwords(strtolower($text), " /-");
    }
?>
