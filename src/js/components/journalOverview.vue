<template>
  <div v-masonry transition-duration="0.3s" item-selector=".item">
    <div v-masonry-tile class="item" v-for="entry in journal">
      {{ entry.title }}
    </div>
</div>
</template>

<script>




module.exports = {
   name: 'jorunaloverview',
    // props: ['id'],
    data: function () {
      return {
         journal: [],
      }
   },
   mounted () {
      this.getEntries();
   },
   methods: {
      getEntries: function () {
         let _this = this;
         axios.get('../journal.json')
         .then(response => {
           this.journal = response.data.data;
        })
      },
   },
}

</script>

<style lang="css" scoped>
</style>