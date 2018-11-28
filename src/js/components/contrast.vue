<template>
  <div class="contrast-toggle" v-on:click="toggleContrast">
    O
  </div>
</template>

<script>

module.exports = {
  name: 'contrast',
  data: function () {
    return {
      entries: []
    }
  },
  mounted () {
    // this.getEntries();
  },
  methods: {
     
  }
}

</script>

<style lang="css" scoped>
</style>