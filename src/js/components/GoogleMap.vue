<template>
  <div>
    <!-- <div class="selected-institution" v-bind:class="{ closed: institutInfo }"> -->
    <div class="selected-institution" v-bind:class="foo">

      <!-- <div class="dismiss" v-on:click="toggleInstitutInfo"></div> -->
      <!-- <div class="mobile-toggle" v-on:click="toggleState()"></div> -->
      <aside class="institutions--overview_child" v-for="entry in institutionsData" v-if="entry.number == inst">
        <div class="dismiss"  v-if="foo == 'selected'" v-on:click="inst = 0; closeList(); zoomOut()"><i>ⓧ</i></div>
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
        <div class="get-list-toggle" v-show="foo == 'closed'" v-on:click="openList()"><strong>Liste Museen </strong> <i>☰</i></div>
        <div class="dismiss" v-if="foo == 'show-list'" v-on:click="closeList()"><strong>Liste schliessen</strong> <i>ⓧ</i></div>
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
     foo: "closed",
     center: new google.maps.LatLng(47.55959860000001,7.588576099999955),
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
},

methods: {

  openList () {
    this.foo = "show-list";
  },
  closeList () {
    this.foo = "closed";
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
   if (item.lat !== null && item.lng !== null) {
    _this.markerCoordinates.push({latitude:parseFloat(item.lat),longitude:parseFloat(item.lng), label: item.title, number:item.number, url:item.url, linecolor:item.shuttleLine});
  }
});
  this.createMap();
});
},
selctedInst () {
 var _this = this;
 var inst = _this.inst;
 var map = _this.map;
 _this.mapTop = true;
  this.foo = "selected";

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
createMap () {
 var _this = this;
 var inst = _this.inst;
 _this.markerCoordinates.forEach(function (coord) {
  var colors = coord.linecolor;

  // console.log(colors);
  if (colors.length > 2 ) {
    var color = '#000000';
  } else {
    var color = colors[0].color;
  }

  var number = coord.number;
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
      strokeColor: color,
      strokeWeight: 1
    },
    number: coord.number,
    url: coord.url,
    map: _this.map,
  });
  marker.addListener('click', toggleBounce);
  google.maps.event.addListener(marker, "click", function(evt) {
   var number = this.number;
   var instNumber = _this.inst;
   var map = this.map;
   _this.inst = number;
   _this.mapTop = true;
    _this.foo = "selected";


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

