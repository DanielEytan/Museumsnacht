<template>
    <div class="ThemeList">
      <h1>My Entries</h1>
      <ul class="list-group">
        <li class="list-group-item" v-for="entry in themes">{{ entry.title }}</li >
      </ul>
    </div>
</template>

<script>

module.exports = {
  name: 'themelist',
  data: function () {
    return {
      themes: []
    }
  },
  mounted () {
    this.getEntries();
  },
  methods: {
    getEntries () {
      let vm = this;
      axios.get('themes.json')
        .then(response => {
          this.themes = response.data.data
        })
    }
  }
}

</script>

<style lang="css" scoped>
</style>