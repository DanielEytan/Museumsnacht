<template>
    <div class="filter__time">
      <vue-slider ref="slider" v-model="value" v-bind="sliderProps"></vue-slider>
    </div>
</template>

<script>
import vueSlider from 'vue-slider-component';

module.exports = {
  name: 'timefilter',
  components: {
    vueSlider
  },
  data () {
    return {
      value: [
        "18:00",
        "02:00"
      ],
      sliderProps: {
        width: "100%",
        height: 4,
        dotSize: 14,
        min: 1,
        max: 100,
        interval: 1,
        disabled: false,
        show: true,
        tooltip: "always",
        data: [
          "18:00",
          "18:15",
          "18:30",
          "18:45",
          "19:00",
          "19:15",
          "19:30",
          "19:45",
          "20:00",
          "20:15",
          "20:30",
          "20:45",
          "21:00",
          "21:15",
          "21:30",
          "21:45",
          "22:00",
          "22:15",
          "22:30",
          "22:45",
          "23:00",
          "23:15",
          "23:30",
          "23:45",
          "00:00",
          "00:15",
          "00:30",
          "00:45",
          "01:00",
          "01:15",
          "01:30",
          "01:45",
          "02:00"
        ]
      }
    }
  },
  watch: {
    'value': function() {
      // When the internal value changes, we $emit an event. Because this event is 
      // named 'input', v-model will automatically update the parent checkedValues data attribute
      this.$emit('input', this.value);
    }
  }
}

</script>

<style lang="css" scoped>
.vue-slider-component .vue-slider-tooltip  {
  /*background: black;*/
  color: red;

}

</style>