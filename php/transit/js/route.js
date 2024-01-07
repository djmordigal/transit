$(document).ready(function() {
    // the server returned a list of all trips for the current line,
    // in the currently selected direction
    // now we connect all the unique stops together into one giant directed
    // graph for the line
    var data = mergeTrips()

    // once that's been done, draw the graph
    const g = ForceGraph()

    // nodeRelSize - set the size of the nodes
    // linkDirectionalParticles - control the speed of moving dots between nodes
    // onNodeHover - each node acts as a hyperlink to details about that stop
    //      and shows any available transfers to other lines
    // onNodeClick - takes you to the stop detail page for that stop (opens a
    //      new tab/window so you don't lose your place)
    g(document.getElementById("myGraph"))
        .nodeRelSize(12)
        .linkDirectionalParticles(1)
        .onNodeHover(nodeHover)
        .onNodeClick(nodeClick)
        .graphData(data)

    // tell user we're done loading
    $(".spinner").hide()
});

/**
 * Consolidates all returned trip data from the server into one graph for the
 * current line, in the currently selected direction.
 *
 * GTFS static data is all about trips, so VERY granular. Each trip represents a
 * single run of a transit vehicle along a route line, and each trip can serve a
 * unique set of stops. Our job now is to aggregate this by connecting the nodes
 * (which represent stops) in a series of unique paths taken by the transit
 * vehicle.
 *
 * @returns the merged data as a connected node structure
 */
function mergeTrips() {
    var trips = details["trips"]    // trip data returned from the server
    var stops = details["stops"]    // data about unique stops
    var route = details["route"]    // details about this route
    var data = {}                   // data object, to be passed to the graph
    var nodes = []                  // array of unique nodes for the graph
    var links = []                  // array of connections between nodes

    for(var i = 0; i < trips.length; i++) {
        // current trip being examined
        var t = trips[i]

        for(var j = 0; j < t.length; j++) {
            // current stop for the current trip
            var stopId = t[j]

            // if the graph does not already contain a node for this stop
            if(!containsNode(nodes, stopId)) {
                // pull the name from the unique stop information
                var nameHTML = stops[stopId]["name"]

                // if any transfers from this stop are available,
                // show those as route bullet indicators
                if(stops[stopId]["transfers"] != null)
                    nameHTML += "<br>" + colorize(stops[stopId]["transfers"])

                // then build the node:
                //  ID = the GTFS stop ID provided by MTA
                //  name = an HTML string containing the stop name + transfers
                //  color = the route background color, provided by MTA
                var n = {
                    id: stopId,
                    name: nameHTML,
                    color: "#" + route["route_color"]
                }

                // add to the node array
                nodes.push(n)
            }

            // if we are not on the last stop for this trip
            if(j < t.length - 1) {
                var src = stopId    // current stop
                var tgt = t[j + 1]  // next logical stop

                // and if there is not an existing link between this stop and
                // the next stop
                if(!containsLink(links, src, tgt)) {
                    // add the link
                    //  source = node representing this stop
                    //  target = node representing the next stop
                    //  color = link color (white)
                    var l = {
                        source: src,
                        target: tgt,
                        color: "white"
                    }
                    links.push(l)
                }
            }
        }
    }

    data.nodes = nodes      // set node and link data for the graph
    data.links = links
    return data             // done ... simple, eh?
}

/**
 * Checks whether a link between two nodes exists in the graph being built.
 *
 * @param links - current array of links
 * @param {*} source - the source node to check
 * @param {*} target - the target node to check
 * @returns true if a link exists between "source" and "target", false otherwise
 */
function containsLink(links, source, target) {
    for(var i = 0; i < links.length; i++) {
        var link = links[i]

        // source and target match what we're looking for
        // found it - done
        if(link.source == source && link.target == target)
            return true
    }

    return false
}

/**
 * Checks whether the graph being built contains a node with the specified ID.
 *
 * @param nodes - current array of nodes
 * @param nodeId - the ID to look for
 * @returns true if a node with the specified ID was found, false otherwise
 */
function containsNode(nodes, nodeId) {
    for(var i = 0; i < nodes.length; i++) {

        // node's ID matches the ID we're looking for
        // found it - done
        if(nodes[i].id == nodeId)
            return true
    }

    return false
}

/**
 * Runs whenever the nodeHover event is fired (basically, as the mouse moves
 * over the graph).
 *
 * This emulates the behavior of the node being a hyperlink, which it is (just
 * not with the proper <a> tag).
 *
 * @param node - the node over which the cursor is hovering, or null if the
 * cursor is not hovering over a node
 */
function nodeHover(node) {
    // is the cursor actually over a node?
    if(node == null)
        $("#myGraph").css("cursor", "default")  // nope, switch to default
    else
        $("#myGraph").css("cursor", "pointer")  // yes, switch to pointer
}

/**
 * Runs whenever a node is clicked, which opens the stop detail page for the
 * stop that the node represents. Opens the page in a new tab or window so you
 * can stay on this page too.
 *
 * @param node - the node that was clicked
 */
function nodeClick(node) {
    var id = node.id
    window.open("stop.php?id=" + id, "_blank")
}

/**
 * Used mainly for a list of transfers for a stop. A list of transfers is just
 * a string of route IDs. This function converts the string of IDs to a "string"
 * of route logos for those IDs.
 *
 * @param str - the string of route IDs
 * @returns a "list" of route images for those IDs
 */
function colorize(str) {
    var imgs = details["images"]    // list of images returned from the server
    var ids = str.split(/\s+/)      // split into individual IDs
    var text = ""                   // building the HTML text
    for(var i = 0; i < ids.length; i++) {
        var path = "img/" + imgs[ids[i]]
        text += "<img src=\"" + path + "\" height=\"25\" width=\"25\"> ";
    }

    return text
}
