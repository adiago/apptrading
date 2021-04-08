<script>
  //Importing Line class from the vue-chartjs wrapper
  import { Line } from 'vue-chartjs'

  //Exporting this so it can be used in other components
  export default {
    extends: Line,
    props: ['chartData'],
    data () {
      return {
       
        //Chart.js options that controls the appearance of the chart
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              },
              gridLines: {
                display: true
              }
            }],
            xAxes: [ {
              gridLines: {
                display: false
              }
            }]
          },
          legend: {
            display: true
          },
         responsive: true,
         maintainAspectRatio: false,
         pieceLabel: {
           mode: 'percentage',
           precision: 1
         }
        }
      }
    },
    methods: {
      update() {
        this.$data._chart.update()
      }
    },
    mounted () {
      //renderChart
      this.renderChart(this.chartData, this.options)
    }
  }
</script>