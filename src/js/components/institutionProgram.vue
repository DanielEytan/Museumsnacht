<template>
  <div>
      <ul class="program__list program__list--selection">
         <li class="program__list__event" v-for="entry in programevents" v-if="entry.parent.number == parent">
              <myprogramevent ref="myprogramevent" :programevent="entry"></myprogramevent>
         </li>
      </ul>
      </div>
</template>

<script>
import saveProgram from './SaveProgram.vue'
import MyProgramEvent from './MyProgramEvent.vue'

module.exports = {
   name: 'institutionprogram',
    props: ['parent'],
    components: {
      myprogramevent: MyProgramEvent,
      saveprogram: saveProgram
    },
    data: function () {
      return {
         programevents: [],
      }
   },
   mounted () {
      this.getEntries();
   },
   methods: {
      getEntries: function () {
         let _this = this;
         axios.get('../programevent.json')
         .then(response => {
           this.programevents = response.data.data;
        })
      },
   },
}

</script>

<style lang="css" scoped>
</style>