<template>
	<div>
		<header class="site-header">
			<h1>Museen</h1>
			<nav>
					<ul>
					<li v-on:click="toggleGridView" v-bind:class="{ active: gridView }"><i>▦</i> <span>Raster</span></li>
					<li v-on:click="toggleListView" v-bind:class="{ active: listView }"><i>☷ <span>Liste</span></i></li>
					<li v-on:click="toggleMapView" v-bind:class="{ active: mapView }"><i>📌 <span>Karte</span></i></li>
				</ul>
			</nav>
		</header>
		<section class="institution--overview--map-view map-view" v-if="mapView">
			<googlemap name="googleMap"></googlemap>
		</section>
		<section class="group of-three institutions--overview--grid-view grid-view" v-show="gridView">
			<div v-for="entry in institutions" class="institutions--overview_child">
				<a v-bind:href="entry.url">
					<figure>
						<img v-bind:src="entry.photos">
					</figure>
					<div v-for="color in entry.shuttleLine" v-if="entry.shuttleLine.length < 2" class="shuttle-line">
						<div class="number"><i v-bind:style="{background: color.color}">{{ entry.number }}</i></div> 
						<div class="lines"><span v-bind:style="{color: color.color}">  {{ color.title }}</span></div>
					</div>
					<div v-if="entry.shuttleLine.length > 1" class="shuttle-line several">
						<div class="number"><i style="background: black">{{ entry.number }}</i></div>
						<div class="lines">
							<span v-for="color in entry.shuttleLine" v-bind:style="{color: color.color}"><nobr>{{ color.title }}</nobr></span>
						</div>
					</div>
					<h1>{{ entry.title }}</h1>
					<h2 class="program-title" id="wordwrap"><span v-if="entry.programmTitle === 'EXPERIMENTIERWERKSTATT'">EXPERIMENTIER-WERKSTATT</span><span v-else>{{ entry.programmTitle }}</span></h2>

					<div class="icons">
						<span v-if="entry.advanceSale == 1">&#127915;</span>
						<span v-for="value in entry.accessibility" v-if="value === 'wheelchair'">&#9855;</span>
						<span v-for="value in entry.accessibility" v-if="value === 'partlyWheelchair'">&#9855;*</span>
						<span v-if="entry.wifi == 1">&#8980;</span>

					</div>

					<!-- <p class="journey" v-html="entry.journey">{{ entry.journey }}</p> -->
				</a>
			</div>
		</section>
		<section class="institutions--overview--list-view list-view" v-show="listView">
			<ul>
				<li v-for="entry in institutions" class="institutions--overview_child">
					<a v-bind:href="entry.url">
						<div>
							<div v-for="color in entry.shuttleLine" v-if="entry.shuttleLine.length < 2" class="shuttle-line">
							<div class="number"><i v-bind:style="{background: color.color}">{{ entry.number }}</i></div> 
							<div class="lines"><span v-bind:style="{color: color.color}">  {{ color.title }}</span></div>
						</div>
						<div v-if="entry.shuttleLine.length > 1" class="shuttle-line several">
							<div class="number"><i style="background: black">{{ entry.number }}</i></div>
							<div class="lines">
								<span v-for="color in entry.shuttleLine" v-bind:style="{color: color.color}"><nobr>{{ color.title }}</nobr></span>
							</div>
						</div>
							<h1>{{ entry.title }}</h1>
							
							<div class="info-bar">
								<address><i>📌</i> {{ entry.address }}<span v-if="entry.location">, {{ entry.location }}</span></address>
								<div class="icons">
									<div v-if="entry.advanceSale == 1">&#127915; Vorverkauf/Abendkasse</div>
									<div v-for="value in entry.accessibility" v-if="value === 'wheelchair'">&#9855; rollstuhlgängig</div>
									<div v-for="value in entry.accessibility" v-if="value === 'partlyWheelchair'">&#9855;* teilweise rollstuhlgängig</div>
									<div v-if="entry.wifi == 1"> &#8980;  Wi-Fi verfügbar</div>

								</div>
							</div>
					</div>
							
						</a>

				</li>

			</ul>
		</section>
	</div>
</template>

<script>
import GoogleMap from './GoogleMap.vue'

module.exports = {
	name: 'institutions',
	components: {
		googlemap: GoogleMap
	},
	data: function () {
		return {
			listView: false,
			mapView: false,
			gridView: true,
			institutions: [],

		}
	},
	mounted () {
		this.getEntries();
	},
	methods: {
		toggleListView () {
			this.listView = !this.listView;
			if (this.listView = true) {
				this.listView = true;
			};
			if (this.mapView = true) {
				this.mapView = false;
			};
			if (this.gridView = true) {
				this.gridView = false;
			};
		},
		toggleGridView () {
			this.gridView = !this.gridView;
			if (this.gridView = true) {
				this.gridView = true;
			};
			if (this.mapView = true) {
				this.mapView = false;
			};
			if (this.listView = true) {
				this.listView = false;
			};
		},
		toggleMapView () {
			this.gridView = !this.gridView;

			if (this.mapView = true) {
				this.mapView = true;
			};
			if (this.listView = true) {
				this.listView = false;
			};
			if (this.gridView = true) {
				this.gridView = false;
			};
		},
		getEntries () {
			let vm = this;
			axios.get('institution.json')
			.then(response => {
  					// var presell = this.presellLocations;
  					// var presell = JSON.parse(string);
  					this.institutions = response.data.data
  				})
		}
	}
}

</script>

<style lang="css" scoped>
</style>