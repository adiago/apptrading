<script>

//Importing Line class from the vue-chartjs wrapper
import { Bar } from "vue-chartjs";

//Exporting this so it can be used in other components
export default {
  extends: Bar,
  props: ['chartData'],
  data () {
    return {
      //Chart.js options that controls the appearance of the chart
      options: {
        responsive: false,
        legend: {
            display: true,
            position: 'top',
        },
        animation: {
            animateScale: true,
            animateRotate: true
        },
        // tooltips: {
          // callbacks: {
            // label: function() {
              // return 'jeje'
            // }
          // }
        // }
      }
    }
  },
    
  methods: {
      update() {
        this.$data._chart.update()
      }
  },
  watch: {
    data: function() {
      this._chart.destroy();
      //this.renderChart(this.data, this.options);
      this.update();
    }
  },
  mounted() {
      //renderChart
      this.renderChart(this.chartData, this.options)
  }
}
</script>