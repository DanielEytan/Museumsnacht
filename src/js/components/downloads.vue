<template>
  <div>
    <div class="blocks"  v-for="entry in downloads">
        <div v-for="block in entry.block">
        <h1 class="block-title">{{ block.title }}</h1>
        <ul class="list-group">
          <li class="list-group-item" v-for="doc in block.docs" v-if="block.type == 'image'">
            yeah
            
          </li>
        </ul>
        </div>
    </div>
  </div>
</template>

<script>

module.exports = {
  name: 'downloads',
  data: function () {
    return {
      downloads: []
    }
  },
  mounted () {
    this.getEntries();
  },
  methods: {
    getEntries () {
      // let vm = this;
      axios.get('downloads.json')
        .then(response => {
          this.downloads = response.data.data
        })
    }
  }
}

</script>

<style lang="css" scoped>
</style>