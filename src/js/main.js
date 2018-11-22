//require
window.axios = require('axios');
window._ = require('lodash');

//import
import Vue from 'vue'
import VueRouter from 'vue-router'
import ThemeList from './components/ThemeList.vue'
import InstitutionFilter from './components/InstitutionFilter.vue'
import CheckboxFilter from './components/CheckboxFilter.vue'
import TimeFilter from './components/TimeFilter.vue'
import ProgramList from './components/ProgramList.vue'
import saveProgram from './components/SaveProgram.vue'
import shareProgram from './components/ShareMyProgram.vue'
import proposedProgram from './components/proposedProgram.vue'
import institutionProgram from './components/institutionProgram.vue'
import journalProgram from './components/journalProgram.vue'

import savedLink from './components/SavedLink.vue'
import counter from './components/Counter.vue'

import MyProgram from './components/MyProgram.vue'
import moment from 'moment.min.js'
import GoogleMap from './components/GoogleMap.vue'
import twitter from './components/twitter.vue'
import contrast from './components/contrast.vue'
import presell from './components/presell.vue'
import institutions from './components/institutions.vue'
import en from './components/en.vue'
import stream from './components/SocialStream.vue'
import downloads from './components/downloads.vue'


import VueClipboard from 'vue-clipboard2'
Vue.use(VueClipboard);

import VueScrollTo from 'vue-scrollto'
Vue.use(VueScrollTo);

Vue.use(VueRouter);

// import VueMasonryPlugin from 'masonry.min.js';
// Vue.use(VueMasonryPlugin)
import journalOverview from './components/journalOverview.vue'


//vue main app instance
var vueApp = new Vue({
    delimiters: ['${', '}'],
    el: '#vue-app',
    components: {
        themelist:ThemeList,
        institutionfilter:InstitutionFilter,
        checkboxfilter:CheckboxFilter,
        timefilter:TimeFilter,
        programlist: ProgramList,
        saveprogram: saveProgram,
        shareprogram: shareProgram,
        proposedprogram: proposedProgram,
        institutionprogram: institutionProgram,
        journalprogram: journalProgram,
        journaloverview: journalOverview,
        en: en,
        socialstream: stream,
        savedlink: savedLink,
        counter: counter,

        myprogram: MyProgram,
        googlemap: GoogleMap,
        twitter: twitter,
        contrast: contrast,
        presell: presell,
        institutions: institutions,
        downloads: downloads,
    },
    data: {
        menuOpen: false,
        headerReduced: false,
        activeContrast: false,
        openInstitution: false,
        openThemes: false,
        openKinds: false,
        openLang: false,
        checkedInstitutions: [],
        checkedThemes: [],
        checkedEvents: [],
        checkedLanguages: [],
        checkedTimes: [],
        institutionsAPITitle: "institution",
        themesAPITitle: "themes",
        eventsAPITitle: "events",
        languagesAPITitle: "languages",
        daysLeft: "81",
        langTickets: true,
        langMobility: false,
        langProgram: false,
        loader: true,

    },
     beforeMount(){
      this.stateContrast(),
      this.time()
      // this.ticketToggle()
    },
    mounted(){
      window.addEventListener("scroll", this.menuScroll);
      this.ifReady();
     
    },
    methods: {
      ifReady: function() {
        this.loader = false;
      },
      ticketToggle: function() {
            this.langTickets = true;
            this.langMobility = false;
            this.langProgram = false;
        },
         mobilityToggle: function() {
            this.langMobility = true;
            this.langTickets = false;
            this.langProgram = false;
        },
        programToggle: function() {
            this.langMobility = false;
            this.langTickets = false;
            this.langProgram = true;
        },
        toggle: function() {
            this.menuOpen = !this.menuOpen;
        },
        menuScroll: function () {
          this.menuOpen = false;

          var doc = document.documentElement;
          var top = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);
          if (top > 50) {
            this.headerReduced = true;
          } else {
            this.headerReduced = false;
          }
            

        },
        time: function() {
            var eventdate = moment("2019-01-19");
            var todaysdate = moment();
            var counter = eventdate.diff(todaysdate, 'days');
            this.daysLeft = counter;
        },
        toggleFilters: function (valueName) {
          var _this = this;
          switch(valueName) {
              case 'institution':
                  _this.toggleInstitution();
                  break;
              case 'themes':
                  _this.toggleThemes();
                  break;
              case 'events':
                  _this.toggleKinds();
                  break;
              case 'languages':
                  _this.toggleLang();
                  break;
              default:
                  break;
          }
        },
        toggleContrast: function() {
            var contrastState = localStorage.getItem('contrast')
            if( contrastState == null ) {
              localStorage.setItem("contrast","active");
              this.activeContrast = true;
            } else if( contrastState == 'active' ) {
              localStorage.setItem("contrast","inactive");
              this.activeContrast = false;
            } else if( contrastState == 'inactive') {
              localStorage.setItem("contrast","active");
              this.activeContrast = true;
            }
        },
        stateContrast: function() {
           var contrastState = localStorage.getItem('contrast')
           var now = moment().format('HH');
            if( contrastState == null ) {
              this.activeContrast = false;
              if( now > 17 || now <= 7) {
                this.activeContrast = true;
                // console.log('its bright out');
              }
            } else if( contrastState == 'active' ) {
              this.activeContrast = true;
            } else if( contrastState == 'inactive') {
              this.activeContrast = false;
            }
        },
         toggleInstitution: function(){
            this.openInstitution = !this.openInstitution;
            if (this.openThemes = true) {
              this.openThemes = !this.openThemes;
            };
             if (this.openKinds = true) {
              this.openKinds = !this.openKinds;              
            };
            if (this.openLang = true) {
              this.openLang = !this.openLang;              
            };
        
        },
         toggleThemes: function(){
            this.openThemes = !this.openThemes;
            if (this.openInstitution = true) {
              this.openInstitution = !this.openInstitution;
            };
             if (this.openKinds = true) {
              this.openKinds = !this.openKinds;              
            };
            if (this.openLang = true) {
              this.openLang = !this.openLang;              
            };
        },
         toggleKinds: function(){
            this.openKinds = !this.openKinds;
            if (this.openInstitution = true) {
              this.openInstitution = !this.openInstitution;
            };
             if (this.openThemes = true) {
              this.openThemes = !this.openThemes;              
            };
            if (this.openLang = true) {
              this.openLang = !this.openLang;              
            };
        },
         toggleLang: function(){
            this.openLang = !this.openLang;
            if (this.openInstitution = true) {
              this.openInstitution = !this.openInstitution;
            };
             if (this.openThemes = true) {
              this.openThemes = !this.openThemes;              
            };
            if (this.openKinds = true) {
              this.openKinds = !this.openKinds;              
            };
        },
        removeFilter: function (filter) {
          // console.log(filter);
          if(this.checkedInstitutions.indexOf(filter) > -1) {
            this.checkedInstitutions.splice(this.checkedInstitutions.indexOf(filter),1);
          }
          if(this.checkedThemes.indexOf(filter) > -1) {
            this.checkedThemes.splice(this.checkedThemes.indexOf(filter),1);
          }
          if(this.checkedEvents.indexOf(filter) > -1) {
            this.checkedEvents.splice(this.checkedEvents.indexOf(filter),1);
          }
          if(this.checkedLanguages.indexOf(filter) > -1) {
            this.checkedLanguages.splice(this.checkedLanguages.indexOf(filter),1);
          }
        },
    },

    
});

Vue.filter('formatDate', function(value) {
  if (value) {
    return moment(String(value)).format("HH:mm")
  }
});


Vue.filter('timeTable', function(value) {
    var translate = {
      "18:00": 0,
      "18:15": 1,
      "18:30": 2,
      "18:45": 3,
      "19:00": 4,
      "19:15": 5,
      "19:30": 6,
      "19:45": 7,
      "20:00": 8,
      "20:15": 9,
      "20:30": 10,
      "20:45": 11,
      "21:00": 12,
      "21:15": 13,
      "21:30": 14,
      "21:45": 15,
      "22:00": 16,
      "22:15": 17,
      "22:30": 18,
      "22:45": 19,
      "23:00": 20,
      "23:15": 21,
      "23:30": 22,
      "23:45": 23,
      "00:00": 24,
      "00:15": 25,
      "00:30": 26,
      "00:45": 27,
      "01:00": 28,
      "01:15": 29,
      "01:30": 30,
      "01:45": 31,
      "02:00": 32
    }

  if (value) {
    return translate[value];
  }
});



Vue.prototype._ = _;