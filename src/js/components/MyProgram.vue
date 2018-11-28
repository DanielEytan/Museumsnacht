<template>
  <div class="program--entry">
    <div v-if="programevents.length == 0" class="empty">
      <p>Dein persönliches Programm ist leer.</p>
      <p>Speichere deine Favoriten unter <a href="/programm">Programm</a> oder <a href="/museen">Museen</a> mit <nobr>Klick auf <span>&nbsp;♥</span></nobr>.</p>
    </div>
    <div v-else>
      <article>
        <div class="intro"><p>Hier sind deine ausgewählten Programmangebote gespeichert. Du kannst die Liste mit deinem Namen personalisieren, sie anschliessend ausdrucken, per Mail versenden oder mit Freunden auf Facebook und Twitter teilen.</p>
        </div>
        <ul class="program__list program__list--selection">
            <li class="program__list__event" v-for="entry in programevents">
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
              <myprogramevent ref="myprogramevent" :programevent="entry"></myprogramevent>
            </div>
          </li>
        </ul>
      </article>
      <div class="control-program">
      <!-- <div class="main-controls">
        <button class="reset-my-program red" v-on:click="resetMyProgram">
          Mein Programm zurücksetzen <i>ⓧ</i>
        </button>
      </div> -->
      <shareprogram :programevent="programevents"></shareprogram>   
    </div>
  </div>
</div>
</template>

<script>
import ProgramEvent from './ProgramEvent.vue'
import SaveProgram from './SaveProgram.vue'
import shareProgram from './ShareMyProgram.vue'
import MyProgramEvent from './MyProgramEvent.vue'



module.exports = {
  name: 'myprogram',
  props: [],
  components: {
    myprogramevent: MyProgramEvent,
    programevent: ProgramEvent,
    saveprogram: SaveProgram,
    shareprogram: shareProgram,
  },
  data: function () {
    return {
      programevents: [],
      items: [],
      itemSaved: false,
      url: '/programm/programm',
      hover: false
    }
  },
  mounted () {
   this.getItems();
   this.getEntries();
   // this.sendItems();
 },

 methods: {
  getEntries: function () {
   let _this = this;
   axios.get('../programevent.json')
   .then(response => {
    var programevents = _.filter(response.data.data, function (o) {
      return (_this.items.indexOf(o.id) !== -1);
    });

    programevents = _.sortBy(programevents,function (o) {
      let date = o.time[0].start.date;
      return o.time[0].start.date;
    });

    _this.programevents = programevents;
  });
 },
 getItems: function () {
  var idListFromLocalStorage = [];
  idListFromLocalStorage = JSON.parse(localStorage.getItem('programId'));
  this.items = idListFromLocalStorage;
},
getParameterByName: function (name, url) {
 if (!url) url = window.location.href;
 name = name.replace(/[\[\]]/g, "\\$&");
 var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
 results = regex.exec(url);
 if (!results) return null;
 if (!results[2]) return '';
 return decodeURIComponent(results[2].replace(/\+/g, " "));
},
toggled: function() {
  this.itemSaved = !this.itemSaved;
},
resetMyProgram: function() {
  var _this = this;
  var myprogramevent_child_components = this.$refs.myprogramevent;
  myprogramevent_child_components.forEach(function(o) {
    o.removeFromList();
  });
  location.reload();
}
}
}
</script>
<style lang="css" scoped>
</style>