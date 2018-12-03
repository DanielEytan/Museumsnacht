<template>
  <div>
    <!-- <div class="selected-institution" v-bind:class="{ closed: institutInfo }"> -->
    <div v-if="!this.isGeolocationAllowed">
      <div class="allow-geolocation" v-on:click="allowGeolocation(true)">
        <strong>Eigenen Standort anzeigen</strong>
      </div>
    </div>
    <div class="selected-institution" v-bind:class="listState">
      <!-- <div class="dismiss" v-on:click="toggleInstitutInfo"></div> -->
      <!-- <div class="mobile-toggle" v-on:click="toggleState()"></div> -->
      <aside class="institutions--overview_child" v-for="entry in institutionsData" v-if="entry.number == inst">
        <div class="dismiss"  v-if="listState == 'selected'" v-on:click="dismissSingleInstutution(); "><i>ⓧ</i></div>
        <div>
          <button v-on:click="inst = 0; zoomOut()">← zur Übersicht</button>
          <div>
            <a v-bind:href="entry.url">
              <figure>
                <img v-bind:src="entry.photos">
              </figure>
              <div v-for="color in entry.shuttleLine" v-if="entry.shuttleLine.length < 2" class="shuttle-line">
                <div class="number"><i v-bind:style="{background: color.color}">{{ entry.number }}</i></div> 
                <div class="lines"><span v-bind:style="{color: color.color}">  {{ color.title }}</span></div>
              </div>
              <div v-if="entry.shuttleLine.length > 1" class="shuttle-line several">
                <div class="number"><i style="background: black">{{ entry.number }}</i></div>
                <div class="lines">
                  <span v-for="color in entry.shuttleLine" v-bind:style="{color: color.color}"><nobr>{{ color.title }}</nobr></span>
                </div>
              </div>
              <h1>{{ entry.title }}</h1>


              <p>{{ entry.address }}</p>
              <p class="journey" v-html="entry.journey">{{ entry.journey }}</p>
              <p class="more">Mehr <i>&rarr;</i></p><br>
            </a>
          </div>
        </div>
      </aside>
      <aside v-if="inst == 0">
        <div class="get-list-toggle" v-show="listState === 'closed' || listState === 'selected'" v-on:click="openList()"><strong>Liste Museen </strong> <i>☰</i></div>
        <div class="dismiss" v-if="listState == 'show-list'" v-on:click="closeList()"><strong>Liste schliessen</strong> <i>ⓧ</i></div>
        <div>

          <!-- <p>Klicken Sie auf einen der Marker auf der Karte oder wählen Sie eine Institution aus der Liste aus.</p><br><br> -->
          <ul class="institutions-list">
           <li v-for="entry in institutionsData" v-on:click="inst = entry.number; selctedInst()">

            <div v-for="color in entry.shuttleLine" v-if="entry.shuttleLine.length < 2" class="shuttle-line">
              <div class="number"><i v-bind:style="{background: color.color}">{{ entry.number }}</i></div> 
            </div>
            <div v-if="entry.shuttleLine.length > 1" class="shuttle-line several">
              <div class="number"><i style="background: black">{{ entry.number }}</i></div>
            </div>

            <strong>{{ entry.title }}</strong>
          </li>

        </ul>
      </div>
    </aside>
  </div>
  <div class="google-map" id="multiMap" v-bind:class="{ top: mapTop }"></div>


</div>
</template>

<script>
export default {
  name: 'googlemap',
  props: ['name', 'institutions'],
  data: function () {
    return {
     mapName: "multiMap",
     markerCoordinates: [],
     map: null,
     bounds: null,
     markers: [],
     institutionsData: [],
     inst: "0",
     institutInfo: true,
     mapTop: false,
     listState: "closed",
     center: new google.maps.LatLng(47.55959860000001,7.588576099999955),
     isGeolocationAllowed: false,
   }
 },

 mounted: function () {
  this.inst;
  this.getEntries();
  this.scrollTop();
  const element = document.getElementById(this.mapName)
  const options = {
    zoom: 12,
    center: this.center,
    disableDefaultUI: false,
    mapTypeControl: false,
    streetViewControl: false,
    label: {
      color: 'white',
      fontWeight: 'bold',
      fontSize: '20px',
    },
    icon: {
      labelOrigin: new google.maps.Point(11, 50),
      url: 'default_marker.png',
      size: new google.maps.Size(22, 40),
      origin: new google.maps.Point(0, 0),
      anchor: new google.maps.Point(11, 40),
    },
    styles: [
    {
      "featureType": "water",
      "elementType": "geometry",
      "stylers": [
      {
                // "color": "#e9e9e9"
                "color": "#1A0042"
              },
              {
                "lightness": 0
              }
              ]
            },
            {
              "featureType": "landscape",
              "elementType": "geometry",
              "stylers": [
              {
               "color": "#f5f5f5"
             },
             {
               "lightness": 20
             }
             ]
           },
           {
             "featureType": "road.highway",
             "elementType": "geometry.fill",
             "stylers": [
             {
              "color": "#ffffff"
            },
            {
              "lightness": 17
            }
            ]
          },
          {
            "featureType": "road.highway",
            "elementType": "geometry.stroke",
            "stylers": [
            {
             "color": "#ffffff"
           },
           {
             "lightness": 29
           },
           {
             "weight": 0.2
           }
           ]
         },
         {
           "featureType": "road.arterial",
           "elementType": "geometry",
           "stylers": [
           {
            "color": "#ffffff"
          },
          {
            "lightness": 18
          }
          ]
        },
        {
         "featureType": "road.local",
         "elementType": "geometry",
         "stylers": [
         {
          "color": "#ffffff"
        },
        {
          "lightness": 16
        }
        ]
      },
      {
       "featureType": "poi",
       "elementType": "geometry",
       "stylers": [
       {
        "color": "#c2ea90"
      },
      {
        "lightness": 20
      }
      ]
    },
    {
     "featureType": "poi.park",
     "elementType": "geometry",
     "stylers": [
     {
      "color": "#dedede"
                // color: "#c2ea90"

              },
              {
                "lightness": 21
              }
              ]
            },
            {
              "elementType": "labels.text.stroke",
              "stylers": [
              {
               "visibility": "on"
             },
             {
               "color": "#ffffff"
             },
             {
               "lightness": 16
             }
             ]
           },
           {
             "elementType": "labels.text.fill",
             "stylers": [
             {
              "saturation": 36
            },
            {
              "color": "#333333"
            },
            {
              "lightness": 40
            }
            ]
          },
          {
            "elementType": "labels.icon",
            "stylers": [
            {
             "visibility": "off"
           }
           ]
         },
         {
           "featureType": "transit",
           "elementType": "geometry",
           "stylers": [
           {
            "color": "#f2f2f2"
          },
          {
            "lightness": 19
          }
          ]
        },
        {
         "featureType": "administrative",
         "elementType": "geometry.fill",
         "stylers": [
         {
          "color": "#fefefe"
        },
        {
          "lightness": 20
        }
        ]
      },
      {
       "featureType": "administrative",
       "elementType": "geometry.stroke",
       "stylers": [
       {
        "color": "#fefefe"
      },
      {
        "lightness": 17
      },
      {
        "weight": 1.2
      }
      ]
    }
    ]
  }
  this.map = new google.maps.Map(element, options);
  this.isGeolocationAllowed = localStorage.getItem('allowGeolocation');
},

methods: {

  openList () {
    this.listState = "show-list";
  },
  closeList () {
    this.listState = "closed";
  },
  dismissSingleInstutution () {
    this.inst = 0; 
    this.closeList(); 
    this.zoomOut();
  },

  /*
  ************************************************************
  *
  *            GEOLOCATION
  *
  ************************************************************
  */

  allowGeolocation(center) {
      localStorage.setItem('allowGeolocation', true);
      this.isGeolocationAllowed = true;

      if(this.isMobile()) {

        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var marker = new google.maps.Marker({
              position: pos,
              map: this.map,
            });

            if(!document.getElementById('center-geo')) {
              var centerControlDiv = document.createElement('div');

              var centerControl = new this.CenterControl(centerControlDiv, this.map);

              centerControlDiv.index = 1;
              this.map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(centerControlDiv);
              centerControl.addEventListener('click', function() {
                this.map.setCenter(pos);
              }.bind(this));
            }

            if(center) {
              this.map.setCenter(pos);
            }

          }.bind(this), function() {
            this.handleLocationError(true, null, this.map.getCenter());
          }.bind(this));
        } else {
          // Browser doesn't support Geolocation
          this.handleLocationError(false, null, this.map.getCenter());
        }
      }

    // } else {
    //   console.log("geo is on!");
    // }

  },
  
  handleLocationError(browserHasGeolocation, infoWindow, pos) {
    console.log(pos);
    console.log(browserHasGeolocation ?
      'Error: The Geolocation service seems to be disabled by your browser.' :
      'Error: Your browser doesn\'t support geolocation.'
    );
    // infoWindow.open(map);
  },

  /*
  ************************************************************
  *
  *            CENTER CONTROL
  *
  ************************************************************
  */
  

  CenterControl(controlDiv, map, pos) {

    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.id = 'center-geo',
    controlUI.style.backgroundColor = '#fff';
    controlUI.style.border = '2px solid #fff';
    controlUI.style.borderRadius = '2px';
    controlUI.style.boxShadow = 'rgba(0, 0, 0, 0.3) 0px 1px 4px -1px';
    controlUI.style.cursor = 'pointer';
    controlUI.style.margin = '10px';
    controlUI.style.textAlign = 'center';
    controlUI.title = 'Click to recenter the map';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    controlText.style.color = 'gba(0, 0, 0, 0.3)';
    // controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    controlText.style.fontSize = '0';
    controlText.style.padding = '6px';
    controlText.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><style type="text/css">.st0{fill:none;}.st1{fill:#666666;}</style><path class="st0" d="M0 0h24v24H0z" fill="none"/><path class="st1" d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3c-.46-4.17-3.77-7.48-7.94-7.94V1h-2v2.06C6.83 3.52 3.52 6.83 3.06 11H1v2h2.06c.46 4.17 3.77 7.48 7.94 7.94V23h2v-2.06c4.17-.46 7.48-3.77 7.94-7.94H23v-2h-2.06zM12 19c-3.87 0-7-3.13-7-7s3.13-7 7-7 7 3.13 7 7-3.13 7-7 7z"/></svg>';
    controlUI.appendChild(controlText);

    return controlUI;

    // Setup the click event listeners: simply set the map to Position.
    // if(pos) {
    //   controlUI.addEventListener('click', function() {
    //     map.setCenter(pos);
    //   });
    // }
  },

  /*
  ************************************************************
  *
  *            ISMOBILE
  *
  ************************************************************
  */
  isMobile () {
    var check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
  },

   //  toggleInstitutInfo () {
   //   this.institutInfo = !this.institutInfo;
   // },
  scrollTop () {
    this.$el.querySelector("#multiMap").scrollTop;
  },

  getEntries () {
    axios.get('../locations.json')
    .then(response => {
      this.institutionsData = response.data.data
      var _this = this;
      response.data.data.forEach(function (item) {
        var lat = item.map ? item.map.lat : null;
        var lng = item.map ? item.map.lng : null;
        if (lat !== null && lng !== null) {
          _this.markerCoordinates.push({
            latitude: parseFloat(lat),
            longitude: parseFloat(lng),
            label: item.title,
            number: item.number,
            url: item.url,
            linecolor: item.shuttleLine
          });
        }
      });
      this.createMap();

      //enable geolocation if allowed

      if(this.isGeolocationAllowed) {
        this.allowGeolocation(false);
      }
    });
  },

  selctedInst () {
    var _this = this;
    var inst = _this.inst;
    var map = _this.map;
    _this.mapTop = true;
    this.listState = "selected";

    this.$el.querySelector("#multiMap").scrollTop;

    _this.markerCoordinates.forEach(function (coord) {
      var number = coord.number;
      if (number == inst) {
        map.setCenter(new google.maps.LatLng(coord.latitude, coord.longitude));
        map.setZoom(18)
      }
    });
  },

  zoomOut () {
    var _this = this;
    var inst = _this.inst;
    var map = _this.map
    map.setZoom(12)
    map.setCenter(new google.maps.LatLng(47.55959860000001,7.588576099999955));
  },

  /*
  ************************************************************
  *
  *            CEATE MAP
  *
  ************************************************************
  */

  createMap () {
    var _this = this;
    var inst = _this.inst;

    // console.log(_this.markerCoordinates)

    _this.markerCoordinates.forEach(function (coord, index) {
      
      // console.log(coord.latitude, coord.longitude)

      var colors = coord.linecolor;
      var color;

      if (colors.length > 2 ) {
        color = '#000000';
      } else {
        color = colors[0].color;
      }

      var number = coord.number;

      // var scale = 15;
      var scale = 15;
      
      const position = new google.maps.LatLng(coord.latitude, coord.longitude);

      const marker = new google.maps.Marker({ 
        animation: google.maps.Animation.DROP,
        position,
        label: {
          text:coord.number, 
          fontFamily: 'main-eb', 
          fontSize: '1.2em',
          color:"rgba(240,240,240,1)",
        },
        icon: {
          path: google.maps.SymbolPath.CIRCLE,
          scale: scale,
          fillColor: color,
          fillOpacity: 1,
          // strokeColor: "gray",
          strokeColor: 'white',
          strokeWeight: 1,
        },
        number: coord.number,
        url: coord.url,
        map: _this.map,
        // optimized: false,
        zIndex: index,
      });

      marker.addListener('click', toggleBounce);

      google.maps.event.addListener(marker, "click", function(evt) {
        var number = this.number;
        var instNumber = _this.inst;
        var map = this.map;
        _this.inst = number;
        _this.mapTop = true;
        _this.listState = "selected";

        map.setZoom(18);
        map.setCenter(marker.getPosition());
        // this.institutInfo = true;
      });
      google.maps.event.addListener(marker, "mouseover", function(evt) {
       var icon = this.getIcon();
       icon.scale=18;
       this.setIcon(icon);
      });
      google.maps.event.addListener(marker, "mouseout", function(evt) {
        var icon = this.getIcon();
        icon.scale=15;
        this.setIcon(icon);
      });
      google.maps.event.addListener(marker, function(evt) {
        var number = this.number;

      });

      function toggleBounce() {
       if (marker.getAnimation() !== null) {
          marker.setAnimation(null);
        } else {
            // marker.setAnimation(google.maps.Animation.BOUNCE);
        }
      };

    });
  },

}
};
</script>
<style scoped>
.google-map {
  width: 100%;
  /*margin: 0 auto 50px;*/
  background: gray;
}
</style>

