<script>
import { Pie } from "vue-chartjs";

export default {
  extends: Pie,
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
  mounted() {
      //renderChart
      this.renderChart(this.chartData, this.options)
  }
}
</script>