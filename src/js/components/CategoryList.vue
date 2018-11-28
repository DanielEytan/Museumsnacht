<template>
    <div class="CategoryList">
      <h1>My Entries</h1>
      <ul class="list-group">
        <li class="list-group-item" v-for="entry in categories">{{ entry.title }}</li >
      </ul>
    </div>
</template>

<script>

module.exports = {
  name: 'categorylist',
  data: function () {
    return {
      categories: []
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
          this.categories = response.data.data
        })
    }
  }
}

</script>

<style lang="css" scoped>
</style>