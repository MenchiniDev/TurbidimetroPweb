let on = true;
function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 10,
      center: { lat: -33.9, lng: 151.2 },
    });
    
    setMarkers(map);
    }
    
    // Data for the markers consisting of a name, a LatLng and a zIndex for the
    // order in which these markers should display on top of each other.
    const beaches = [
    ["Bondi Beach", -33.890542, 151.274856, 4],
    ["Coogee Beach", -33.923036, 151.259052, 5],
    ["Cronulla Beach", -34.028249, 151.157507, 3],
    ["Manly Beach", -33.80010128657071, 151.28747820854187, 2],
    ["Maroubra Beach", -33.950198, 151.259302, 1],
    ];
    
    function setMarkers(map) {
    // Adds markers to the map.
    // Marker sizes are expressed as a Size of X,Y where the origin of the image
    // (0,0) is located in the top left of the image.
    // Origins, anchor positions and coordinates of the marker increase in the X
    // direction to the right and in the Y direction down.
    const image = {
      url: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png",
      // This marker is 20 pixels wide by 32 pixels high.
      size: new google.maps.Size(20, 32),
      // The origin for this image is (0, 0).
      origin: new google.maps.Point(0, 0),
      // The anchor for this image is the base of the flagpole at (0, 32).
      anchor: new google.maps.Point(0, 32),
    };
    // Shapes define the clickable region of the icon. The type defines an HTML
    // <area> element 'poly' which traces out a polygon as a series of X,Y points.
    // The final coordinate closes the poly by connecting to the first coordinate.
    const shape = {
      coords: [1, 1, 1, 20, 18, 20, 18, 1],
      type: "poly",
    };
    
    for (let i = 0; i < beaches.length; i++) {
      const beach = beaches[i];
    
      new google.maps.Marker({
        position: { lat: beach[1], lng: beach[2] },
        map,
        icon: image,
        shape: shape,
        title: beach[0],
        zIndex: beach[3],
      });
    }
    }
    
    window.initMap = initMap;


function retLat()
{
  console.log("ecocci");
  let el = document.getElementById("addDiv");
  el.style.left="200vh";
  el.style.transitionDuration="300ms";

  let el1 = document.getElementById("rmDiv");
  el1.style.left="200vh";
  el1.style.transitionDuration="300ms";

  let el2 = document.getElementById("mdDiv");
  el2.style.left="200vh";
  el2.style.transitionDuration="300ms";
}

function moveLateral(n)
{
  if(n==1)
  {
    let el = document.getElementById("addDiv");
    el.style.left="150vh";
    el.style.transitionDuration="300ms";
  }  
  else if(n==2)
  {
    let el = document.getElementById("rmDiv");
    el.style.left="150vh";
    el.style.transitionDuration="300ms";
  }else if(n==3)
  {
    let el = document.getElementById("mdDiv");
    el.style.left="150vh";
    el.style.transitionDuration="300ms";
  }
}

//document.getElementById("lateralAdd").addEventListener("click", moveLateral);
//document.getElementById("lateralRm").onclick = moveLateral;
//document.getElementById("lateralMd").onclick = moveLateral;

//document.getElementById("addbtn").onclick = "retLat";
/*document.getElementById("addbtn").onclick = retLat;

document.getElementById("mdbtn").onclick = retLat;
document.getElementById("rmbtn").onclick = retLat;
window.onclick = function(event) {
  console.log("ciao");
  if (event.target == document.getElementById("addDiv")) {

    let el2 = document.getElementById("addDiv");
    el2.style.left="200px";
    el2.style.transitionDuration="50ms";
  }
}*/

function showNotifiche()
{
  let el = document.getElementById("allNotifiche");
  if(on)
  {
    el.style.transitionDuration="300ms";
    el.style.transitionTimingFunction="ease-out";
    el.style.opacity="1";
    el.style.marginTop="20px";
    on = false;
  }else
  {
    el.style.opacity="0";
    on = true;
  }

  let r = document.getElementById("exitAll");
  r.style.opacity="1";
  r.style.top="120px";
  r.style.transitionDuration="700ms";
  r.style.transitionTimingFunction="ease-out";

  document.getElementById("notifImg").setAttribute("src","./img/notification.png");
  console.log("fatto");
}
