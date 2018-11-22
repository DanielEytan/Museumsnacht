<template>
    <div class="InstitutionFilter">
      <h1>My Institutions</h1>
      <ul class="list-group">
        <li class="list-group-item" v-for="entry in institutions">
          <input type="checkbox" v-bind:id="'program-input-'+entry.id" v-bind:value="entry.title" v-model="internalCheckedInstitutions">
          <label v-bind:for="entry.title">{{ entry.title }}</label>
        </li >
      </ul>
      <span>Checked Museums: {{ internalCheckedInstitutions }}</span>

    </div>
</template>

<script>

module.exports = {
  name: 'institutionfilter',
  props: ['checkedInstitutions'],
  data: function () {
    return {
      institutions: [],
      internalCheckedInstitutions: []
    }
  },
  watch: {
    'internalCheckedInstitutions': function() {
      // When the internal value changes, we $emit an event. Because this event is 
      // named 'input', v-model will automatically update the parent checkedInstitutions data attribute
      this.$emit('input', this.internalCheckedInstitutions);
    }
  },
  // created: function() {
  //   // We initially sync the internalCheckedInstitutions with the checkedInstitutions passed in by the parent
  //   this.internalCheckedInstitutions = this.checkedInstitutions;
  // },
  mounted () {
    this.getEntries();
  },
  methods: {
    getEntries () {
      let _this = this;
      axios.get('institution.json')
        .then(response => {
          this.institutions = response.data.data;
          this.initInstitutions();
        })
    },
    initInstitutions () {
      this.$emit('init-institutions', _.map(this.institutions,'title'));
    }
  }
}

</script>

<style lang="css" scoped>
</style>