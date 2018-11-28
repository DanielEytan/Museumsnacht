<template>
   <div class="program--entry">
    <header class="site-header">
      <h1 v-if="name">PROGRAMM von: {{ name }}</h1>
      <h1 v-else>Empfangenes Programm</h1>

    </header>
      <div  class="name">
         <p v-if="name"><strong>Dieses Program wurde von {{ name }} zusammengestellt.</strong></p>
         <p>Mit Klick auf ♥ kannst du einzelne Angebote in dein Programm integrieren.</p>
      </div>
      <ul class="program__list program__list--selection">
        
         <div v-for="id in ids">
         <li class="program__list__event" v-for="entry in programevents" v-if="entry.id == id">
             <!-- <div v-for="color in entry.parent.shuttleLine" class="institution">
                  <span class="suttle-line" v-bind:style="{color: color.color}"> {{ entry.parent.number }} {{ color.title }}</span><br>
                  <a v-bind:href="entry.parent.url">{{ entry.parent.title }}</a><br>
               </div> -->
               <div>
                <section class="institution">
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
<!--                <h1>{{ entry.title }}</h1>
               <div v-for="time in entry.time">
                  <time v-if="time.type === 'setTimes'"> {{ time.start.date | formatDate }} <span v-if="time.duration == true">(Dauer: {{ time.duration }})</span><br></time>
                  <time v-if="time.type === 'continuous'"> {{ time.start.date | formatDate }} – {{ time.end.date | formatDate }} (Durchgehend)<br></time>
                  <time v-if="time.type === 'iterating'">  {{ time.start.date | formatDate }} – {{ time.end.date | formatDate }} ({{ time.frequency }}, Dauer: {{ time.duration }})<br></time>
               </div>
               <div v-html="entry.description">{{ entry.description }}</div> -->
                <myprogramevent ref="myprogramevent" :programevent="entry"></myprogramevent>
                </div>
         </li>
         </div>
      </ul>
      <div class="adapt-program">
      <button class="add-to-my-program" v-on:click="addToMyProgram">
          <i v-bind:class="{ active: adaptProgram }">❤</i> Alle auswählen und in Mein Programm integrieren
        </button>
        <p v-show="adaptProgram"><a href="https://museumsnacht.ch/programm/mein-programm">zu Mein Programm wechseln</a></p>
        </div>
   </div>
</template>

<script>
import MyProgramEvent from './MyProgramEvent.vue'

module.exports = {
   name: 'proposedprogram',
   components: {
      myprogramevent: MyProgramEvent
   },
   data: function () {
      return {
         url: '',
         ids: '',
         programevents: [],
         name: '',
         adaptProgram: false,

      }
   },
   mounted () {
      this.getUrl();
      this.getEntries();
      this.splitIds();
      this.getName();
   },
   methods: {
      getEntries: function () {
         let _this = this;
         axios.get('../programevent.json')
         .then(response => {
           this.programevents = response.data.data;
        })
      },
      getUrl: function () {
         // var urlParams = new URLSearchParams(window.location.search);
         // var ids = urlParams.get('ids');
         // var ids = ids.split(" ");
         // console.log(ids);
         // this.ids = ids;
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        };
        var ids = getUrlParameter('ids');
         var ids = ids.split(" ");
         this.ids = ids;
        
      },
      getName: function () {
         // var urlParams = new URLSearchParams(window.location.search);
         // var name = urlParams.get('name');
         // this.name = name;
         function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        };
        var name = getUrlParameter('name');;
        this.name = name;
      },
      splitIds: function () {

         var program = this.programevents;
         var idsEntries = []
         var length = program.length;   
         for (var i = 0; i < length; i++) {
            var item = program[i]
            var itemId = item.id;
         }
      },
      addToMyProgram: function () {
        var _this = this;
        var myprogramevent_child_components = this.$refs.myprogramevent;
        myprogramevent_child_components.forEach(function(o) {
          o.saveProgram(false);
        });
        this.adaptProgram = true;
      }
   },
}

</script>

<style lang="css" scoped>
/*  .add-to-my-program {
    cursor: pointer;
    padding: 10px;
    border: 1px solid black;
    display: inline-block;
    margin-bottom: 30px;
  }*/
</style>