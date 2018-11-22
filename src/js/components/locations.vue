<template>
    <div class="locations">
      <ul class="list-group">
        <li class="list-group-item" v-for="entry in entries">{{ entry.title }}</li >
      </ul>
    </div>
</template>

<script>

module.exports = {
  name: 'locations',
  data: function () {
    return {
      entries: []
    }
  },
  mounted () {
    this.getEntries();
  },
  methods: {
    getEntries () {
      let vm = this;
      axios.get('locations.json')
        .then(response => {
          this.entries = response.data.data
        })
    }
  }
}

</script>

<style lang="css" scoped>
</style>