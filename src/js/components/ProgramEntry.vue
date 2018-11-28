<template>
    <li class="program__list--complete__block program--block" v-if="showProgramEvents">
      <div>
        <section class="institution">
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

          <h1><a :href="entry.url">{{ entry.title }}</a></h1>

            

          <br>
          <h2>{{ entry.programmTitle }}</h2>
        <!-- <p>{{ entry.address }}<br> – {{ entry.journey }}</p><br> -->


        </section>
        <!-- <div v-html="entry.programmText"></div> -->

        <ul class="program__list--complete__event-list">
          <programevent v-for="programevent in entry.events" :key="programevent.id" :programevent="programevent" :programevent-is-visible="programevent.isVisible" :institution="entry.title" :checked-institutions="checkedInstitutions" :checked-themes="checkedThemes" :checked-events="checkedEvents" :checked-languages="checkedLanguages" :checked-times="checkedTimes"></programevent>
        </ul>
       <div class="food"><h3>Essen und trinken:</h3><p><strong>{{ entry.food }}</strong></p></div>
      </div>
    </li>
</template>

<script>
import ProgramEvent from './ProgramEvent.vue'

module.exports = {
  name: 'programentry',
  props: ['entry','checkedInstitutions','checkedThemes','checkedEvents','checkedLanguages','checkedTimes'],
  components: {
    programevent: ProgramEvent
  },
  data: function () {
    return {
      internalNumberOfEvents: 0
    }
  },
  watch: {
    internalNumberOfEvents: function () {
      var changes = {};
      changes[this.entry.id] = this.internalNumberOfEvents;
      this.$emit('update-event-number-of-entry', changes);
    }
  },
  methods: {
    padNumber: function (n) {
      return (n < 10) ? '0' + n.toString() : n.toString();
    },
    checkIntersectionForFilters: function (checkedSelection, eventSpecificEntries){
      var inCheckedSelection = (_.intersectionWith(checkedSelection, _.map(eventSpecificEntries, 'title') , _.isEqual).length > 0) || (checkedSelection.length === 0);
      return inCheckedSelection;
    },
    checkIntersectionForTimeFilter: function (selectedTimes, eventTime){
      var isOneInRange = false
      var selectedStart = this.$options.filters.timeTable(selectedTimes[0]);
      var selectedEnd = this.$options.filters.timeTable(selectedTimes[1]);
      var _this = this;
      for(let time of eventTime) {
        var eventStart = this.$options.filters.timeTable(this.$options.filters.formatDate(time.start.date));
        if ( ( eventStart >= selectedStart ) && (eventStart <= selectedEnd) ) {
          isOneInRange = true;
          break;
        } else {
          // if time is not in range, calculate if it is not full 15 min and round down to earlier 15min interval.
          var old_time = String(this.$options.filters.formatDate(time.start.date));
          var minutes = parseInt(old_time.slice(old_time.indexOf(":")+1));
          var hours = parseInt(old_time.substr(0,old_time.indexOf(":")));
          // console.log("hours: ",hours);
          // console.log("minutes: ",minutes);
          var m = (parseInt((minutes + 7.5)/15) * 15) % 60;
          if(m > 0) {
            m -= 15;
          }
          var h = minutes > 52 ? (hours === 23 ? 0 : ++hours) : hours;
          // console.log("h: ",h);
          // console.log("m: ",m);
          var new_time = _this.padNumber(h)+":"+_this.padNumber(m);

          // console.log(old_time,new_time);
          var newEventStart = this.$options.filters.timeTable(new_time);

          // console.log("bed time: ",newEventStart);
          if ( ( newEventStart >= selectedStart ) && (newEventStart <= selectedEnd) ) {
            isOneInRange = true;
            break;
          }
        }
      }
      return isOneInRange;
    },
    programEventIsVisible: function (programevent) {
      var programEventIsVisible = false

      var inCheckedInstitutions =  this.checkedInstitutions.includes(this.entry.title) || (this.checkedInstitutions.length == 0)

      var inCheckedThemes = this.checkIntersectionForFilters(this.checkedThemes, programevent.themes);

      var inCheckedKindOfEvents = this.checkIntersectionForFilters(this.checkedEvents, programevent.kindOfEvent);

      var inCheckedLanguages = this.checkIntersectionForFilters(this.checkedLanguages, programevent.languages);

      var inSelectedTime = this.checkIntersectionForTimeFilter(this.checkedTimes, programevent.time);


      programEventIsVisible = inCheckedInstitutions && inCheckedThemes && inCheckedKindOfEvents && inCheckedLanguages && inSelectedTime;

      // if(!programEventIsVisible) {
      //   console.log(inCheckedInstitutions);
      //   console.log(inCheckedThemes);
      //   console.log(inCheckedKindOfEvents);
      //   console.log(inCheckedLanguages);
      //   console.log(inSelectedTime);
      // }

      return programEventIsVisible;
    },
    numberOfEventsOfEntry: function () {
      var counter = 0;
      var childStatusList = {};

      // console.log(this.entry.events)
      var numberOfEvents = _.reduce(this.entry.events)
      for (let programevent of this.entry.events) {
        if(this.programEventIsVisible(programevent)) {
          counter++;
        } else {
          // console.log("hidden: ",programevent);
        }
      }
      this.internalNumberOfEvents = counter;
      return counter;
    }
  },
  computed: {
    showProgramEvents: function () {
      var programEntryIsVisible = true;
      var childStatusList = [];

      for (let programevent of this.entry.events) {
        programevent.isVisible = this.programEventIsVisible(programevent);
        childStatusList.push(programevent.isVisible);
      }
      if(childStatusList.includes(true)) {
        programEntryIsVisible = true;
      } else {
        programEntryIsVisible = false;
      }
      this.numberOfEventsOfEntry();
      return programEntryIsVisible;
    }
  }
}

</script>

<style lang="css" scoped>
</style>