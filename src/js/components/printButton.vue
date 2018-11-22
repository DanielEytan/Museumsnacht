<template>
  <div class="print-button">
    <button v-on:click="print"><i>⎙ </i> Drucken</button>
  </div>
</template>

<script>

module.exports = {
  name: 'printbutton',
  data: function () {
    return {
    }
  },
  methods: {
    print: function () {
      window.print();
    }
  }
}

</script>

<style lang="css" scoped>
</style>