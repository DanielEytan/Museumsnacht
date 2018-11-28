<template>
 <div>
    <!-- <div class="mn-date" v-if="items.length == 0"><span>Noch {{ days }} Nächte bis zur Museumsnacht</span></div> -->
    <div class="saved-link" v-bind:class="{ active: activeHearth }"><a :href="url">♥ <span v-if="this.items.length > 0">{{ items.length }}</span></a></div>
 </div>

 <!-- <div class="saved-link"><a :href="url">Saved Elements: <span>{{ items }}</span></a></div> -->

</template>

<script>
  import { EventBus } from '../event-bus.js';
  module.exports = {
    name: 'savedlink',
    props: ['days'],
    data: function () {
      return {
        url: '/programm/mein-programm',
        items: [],
        activeHearth: false,
      }
    },
  mounted () {
    this.savedLinks();
    this.initEventBus();
  },
  methods: {

    initEventBus: function () {
        // Listen for the i-got-clicked event and its payload.
        var _this = this;
        EventBus.$on('program-saved', function () {
           _this.savedLinks();
           if (_this.items.length > 0) {
          _this.activeHearth = true;
          } else {
          _this.activeHearth = false;

          }
        });
     },
    savedLinks: function () {
      var idList = JSON.parse(localStorage.getItem('programId'));
      this.items = idList !== null ? idList : [];
      if (this.items.length > 0) {
          this.activeHearth = true;
          }
    }
  }
  }

</script>

<style lang="css" scoped>
</style>