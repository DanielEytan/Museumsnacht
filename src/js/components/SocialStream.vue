<template>
    <div class="social-stream">
      <div v-for="entry in instagram">
        
        <figure v-if="entry.type == 'image'">
          <img v-bind:src="entry.images.standard_resolution.url">
        </figure>
        <video v-else autoplay v-bind:src="entry.videos.standard_resolution.url">
          Sorry, your browser doesn't support embedded videos
        </video>
        <div class="user"><h1>{{ entry.user.username }}</h1></div>
        
        <div class="caption">
          {{ entry.caption.text }}
        </div><br>
      </div>
      <!-- {{ facebook }} -->
    </div>
</template>

<script>
module.exports = {
  name: 'socialstream',
  data: function () {
    return {
      instagram: [],
      facebook: []
    }
  },
  mounted () {
    this.getEntries();
  },
  methods: {
    getEntries () {
      axios.get('https://api.instagram.com/v1/tags/basel/media/recent?access_token=37839051.b3f3b18.06fdb246384d410f9aba55d4d193468d')
        .then(response => {
          this.instagram = response.data.data
        })
    },
    getFacebookEntries () {
      // var hash = 'https://graph.facebook.com/search?q=%23selfie&type=post&access_token=520886231601529|3CU-b-_kB8215hjE60TCCp_t7MI';
      var profile = 'https://graph.facebook.com/v2.11/385482891506278/feed?access_tkoen=520886231601529|3CU-b-_kB8215hjE60TCCp_t7MI';
      axios.get(profile)
        .then(response => {
          this.facebook = response.data.data
        })
    }
  }
}

</script>

<style lang="css" scoped>

</style>