<template>
<div>
    <ul v-if="posts && posts.length">
    <li v-for="post of posts">
      <p><strong>{{post.title}}</strong></p>
      <p>{{post.body}}</p>
    </li>
  </ul>
  <ul v-if="errors && errors.length">
    <li v-for="error of errors">
      {{error.message}}
    </li>
  </ul>
</div>
</template>

<script>
import axios from 'axios';
const HTTP = axios.create({
  baseURL: `  https://api.twitter.com/oauth2/token`,
  headers: {
    Authorization: '1IsdUv633LsaVC71QdlhCmf3z'
  }
})
module.exports = {
  name: 'twitter',
  data() {
    return {
      posts: [],
      errors: []
    }
  },

  // Fetches posts when the component is created.
  created() {
    HTTP.get(`posts`)
    .then(response => {
      this.posts = response.data
    })
    .catch(e => {
      this.errors.push(e)
    })
  

    // async / await version (created() becomes async created())
    //
    // try {
    //   const response = await axios.get(`http://jsonplaceholder.typicode.com/posts`)
    //   this.posts = response.data
    // } catch (e) {
    //   this.errors.push(e)
    // }
  }
}
</script>