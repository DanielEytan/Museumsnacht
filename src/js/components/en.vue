<template>
  <div>
    <div v-for="entry in ticketsData">
      <div v-html="entry.tickets">{{ entry.tickets }}</div>
    </div>
    <div v-for="entry in mobilityData">
      <div v-html="entry.mobility">{{ entry.mobility }}</div>
    </div>
    <div class="program">
      <ul class="program__list program__list--selection">

        <!-- <div v-for="item in items"> -->
          <!-- {{ item }} -->
                {{programevent.languages }}

          <li class="program__list__event" v-for="entry in programevent">
            <!-- <div v-if="entry.languages.title == 'Deutsch'"> -->
              <div>

              <h1>{{ entry.title }}</h1>
           <!--   <section class="institution">
               <div v-for="color in entry.parent.shuttleLine" v-if="entry.parent.shuttleLine.length < 2" class="shuttle-line">
                <div class="number"><i v-bind:style="{background: color.color}">{{ entry.parent.number }}</i></div> 
                <div class="lines"><span v-bind:style="{color: color.color}">  {{ color.title }}</span></div>
              </div>
              <div v-if="entry.parent.shuttleLine.length > 1" class="shuttle-line several">
                <div class="number"><i style="background: black">{{ entry.parent.number }}</i></div>
                <div class="lines">
                  <span v-for="color in entry.parent.shuttleLine" v-bind:style="{color: color.color}"><nobr>{{ color.title }}</nobr></span>
                </div>
              </div>
              <h1><a :href="entry.parent.url">{{ entry.parent.title }}</a></h1>
            </section>
            <div class="program__list__event__info--wrapper flex-container">
             <div class="time">
              <div  v-for="time in entry.time">
                <time v-if="time.type === 'setTimes'"> {{ time.start.date | formatDate }}<span v-if="time.duration.length"><br>(Dauer: {{ time.duration }})</span></time> 
                <time v-if="time.type === 'continuous'"> {{ time.start.date | formatDate }} – {{ time.end.date | formatDate }}</time>
                <time v-if="time.type === 'iterating'">  {{ time.start.date | formatDate }} – {{ time.end.date | formatDate }} <br>({{ time.frequency }}<span v-if="time.duration.length">, Dauer: {{ time.duration }}</span>)</time>
              </div>
            </div>
            <div class="text">
              <h1 class="event-title">{{ entry.title }}</h1>
              <div v-html="entry.description">{{ entry.description }}</div>
            </div>
            <div class="icons">
              <div v-for="kindOfEvent in entry.kindOfEvent" v-if="kindOfEvent.title == 'Für Kinder & Familien'"><span >🎠</span></div>
              <div v-for="language in entry.languages">
                <span v-if="language.title == 'Deutsch'" class="de">⚀</span>
                <span v-if="language.title == 'Englisch'" class="en">⚂</span>
                <span v-if="language.title == 'Französisch'" class="fr">⚁</span>
                <span v-if="language.title == 'Italienisch'" class="it">⚃</span>
                <span v-if="language.title == 'Spanisch'" class="other">⚄</span>
              </div>
            </div>
            <ul class="categories">
              <li v-for="theme in entry.themes"><span><nobr>{{ theme.title }}</nobr></span></li>
              <li v-for="kindOfEvent in entry.kindOfEvent"><span v-if="kindOfEvent.title != 'Für Kinder & Familien'"><nobr>{{ kindOfEvent.title }}</nobr></span></li>
            </ul>
          </div> -->
        </div>
      </li>
    </ul>
  </div>
</div>

</template>

<script>
module.exports = {
  name: 'en',
  // components: {
  //   myprogramevent: MyProgramEvent,
  //   programevent: ProgramEvent
  // },
  data: function () {
    return {
     ticketsData: [],
     mobilityData: [],
     programevent: []
   }
 },
 mounted () {
  this.tickets();
  this.mobility();
  this.program();

},
methods: {
  tickets: function () {
   let _this = this;
   axios.get('../tickets.json')
   .then(response => {
     this.ticketsData = response.data.data;
   })
 },
 mobility: function () {
   let _this = this;
   axios.get('../mobility.json')
   .then(response => {
     this.mobilityData = response.data.data;
   })
 },
 mobility: function () {
   let _this = this;
   axios.get('../mobility.json')
   .then(response => {
     this.mobilityData = response.data.data;
   })
 },
 program: function () {
   let _this = this;
   axios.get('../programevent.json')
   .then(response => {
           this.programevent = response.data.data;
         })
 },
},
}

</script>

<style lang="css" scoped>
</style>