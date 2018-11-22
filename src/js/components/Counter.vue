<template>
    <h1>Noch <strong class="count">{{ days }}</strong> Nächte bis zur Museumsnacht Basel</h1>
    <!-- <div class="saved-link" v-if="items.length > 0"><a :href="url">♥ {{ items.length }} <span>– MEIN PROGRAMM</span></a></div> -->

 <!-- <div class="saved-link"><a :href="url">Saved Elements: <span>{{ items }}</span></a></div> -->

</template>

<script>
  module.exports = {
    name: 'counter',
    props: ['days'],
    data: function () {
      return {

      }
    },
  }

</script>

<style lang="css" scoped>
</style>