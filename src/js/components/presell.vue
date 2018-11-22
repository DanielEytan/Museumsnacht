<template>
 	<div>
 		<div class="toggle-list" v-bind:class="{ open: listOpen }"><span v-on:click="toggleList" class="presell-toggle"><i>+</i> Zusätzliche Vorverkaufsstellen</span></div>
		<div v-for="block in presellLocations" class="presell-location">
			<div v-for="entry in block.list">
				<div v-html="entry.listContent">
          {{ entry.listContent }}    
        </div>
      </div>
		</div>
 	</div>
</template>

<script>

module.exports = {
	name: 'presell',
  	data: function () {
    	return {
      	listOpen: true,
      	presellLocations: []
    	}
  	},
  	mounted () {
  		this.getEntries();
  	},
  	methods: {
  		toggleList () {
  			this.listOpen = !this.listOpen;	
  		},
  		getEntries () {
  			let vm = this;
  			axios.get('visit.json')
  				.then(response => {
  					// var presell = this.presellLocations;
  					// var presell = JSON.parse(string);
  					this.presellLocations = response.data.data
  				})
  		}
  	}
}

</script>

<style lang="css" scoped>
</style>