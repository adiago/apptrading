<style>
.center-card {
    margin-left: auto ;
    margin-right: auto ;
}
</style>

<template>
    <div class="row py-3">
        <div class="col-sm-12 pa-15 static-position">
            <div class="card my-3 pa-0">
                <div class="card-header">Sumatorio Diario</div>
                <div class="card-body">
                    <linechart ref="radarChart" v-if="datasetWinLoss.loaded" :chart-data="datasetSumDiary"></linechart>
                </div>
            </div>
        </div>
        <div class="col-sm-12 pa-15 static-position">
            <div class="card my-3 pa-0">
                <div class="card-header">Operaciones Positivas/Negativas</div>
                <div class="card-body center-card">
                    <piechart ref="upDownChart" v-if="datasetSumDiary.loaded" :chart-data="datasetWinLoss"></piechart>
                </div>
            </div>
        </div>
        <div class="col-sm-12 pa-15 static-position">
            <div class="card my-3 pa-0">
                <div class="card-header">Operaciones Largo/Corto</div>
                <div class="card-body center-card">
                    <piechart ref="longShortChart" v-if="datasetLongShort.loaded" :chart-data="datasetLongShort"></piechart>
                </div>
            </div>
        </div>
        <!-- <div class="col-sm-12 pa-15 static-position">
            <div class="card my-3 pa-0">
                <div class="card-header">Operaciones por horas</div>
                <div class="card-body center-card">
                    <barchart ref="perHoursChart" v-if="datasetPerHours.loaded" :chart-data="datasetPerHours"></barchart>
                </div>
            </div>
        </div> -->
    </div>
</template>

<script>
import PieChart from "./PieChart.vue"
import BarChart from "./BarChart.vue"


  export default {
    components: { PieChart, BarChart},
    name: 'VueChartJS',
    data: () => ({
        // // datasetPerHours: {
        //     loaded: false,
        //     labels: ["2015-01", "2015-02", "2015-03", "2015-04", "2015-05", "2015-06", "2015-07", "2015-08", "2015-09",
        //     "2015-10", "2015-11", "2015-12"],
        //     datasets: [
        //     {
        //         label: 'Bar Chart',
        //         borderWidth: 1,
        //         backgroundColor: [
        //         'rgba(255, 99, 132, 0.2)',
        //         'rgba(54, 162, 235, 0.2)',
        //         'rgba(255, 206, 86, 0.2)',
        //         'rgba(75, 192, 192, 0.2)',
        //         'rgba(153, 102, 255, 0.2)',
        //         'rgba(255, 159, 64, 0.2)',
        //         'rgba(255, 99, 132, 0.2)',
        //         'rgba(54, 162, 235, 0.2)',
        //         'rgba(255, 206, 86, 0.2)',
        //         'rgba(75, 192, 192, 0.2)',
        //         'rgba(153, 102, 255, 0.2)',
        //         'rgba(255, 159, 64, 0.2)'
        //         ],
        //         borderColor: [
        //       'rgba(255,99,132,1)',
        //       'rgba(54, 162, 235, 1)',
        //       'rgba(255, 206, 86, 1)',
        //       'rgba(75, 192, 192, 1)',
        //       'rgba(153, 102, 255, 1)',
        //       'rgba(255, 159, 64, 1)',
        //       'rgba(255,99,132,1)',
        //       'rgba(54, 162, 235, 1)',
        //       'rgba(255, 206, 86, 1)',
        //       'rgba(75, 192, 192, 1)',
        //       'rgba(153, 102, 255, 1)',
        //       'rgba(255, 159, 64, 1)'
        //     ],
        //         data: [12, 19, 3, 5, 2, 3, 20, 3, 5, 6, 2, 1]
        //     }],
        // },

        datasetLongShort: {
            loaded: false,
            labels: ['Largo', 'Corto'],
            datasets: [
            {
                borderWidth: 3,
                backgroundColor: [
                    '#41B883',
                    '#E46651'
                ],
                data: []
            }],
        },
        
        datasetWinLoss: {
            loaded: false,
            labels: ['Positivo', 'Negativo', 'Break Even'],
            datasets: [
            {
                borderWidth: 3,
                backgroundColor: [
                    '#41B883',
                    '#E46651',
                    '#343a40',
                ],
                data: []
            }],
        },
        datasetSumDiary: {
            loaded: false,
            labels: [],
            
            datasets: [
                {
                    label: 'Puntos',
                    lineTension: 0, 
                    fill: false,
                    pointBackgroundColor: '#865390',
                    borderWidth: 3,
                    pointBorderColor: '#865390',
                    //Data to be represented on y-axis
                    data: []
                }
            ]
        }
    }),

    methods: {
            loadDataSumDiary() {
                var vm = this
                axios.get('/chart-points')
                    .then((response) => {
                        var objs = response.data
                        var total = 0
                        var ordered = {};
                        Object.keys(objs).sort(function(a,b) {
                            a = a.split('/').reverse().join('')
                            b = b.split('/').reverse().join('')
                            return a.localeCompare(b)
                        }).forEach(function(key) {
                            total += objs[key]
                            ordered[key] = total;
                        });

                        vm.datasetSumDiary.labels = Object.keys(ordered)
                        vm.datasetSumDiary.datasets[0].data = Object.values(ordered)
                        vm.datasetSumDiary.loaded = true
                    });

            },
            loadDataWinLoss() {
                var vm = this
                axios.get('/chart-winloss').then((response) => {
                    console.log('loadDataWinLoss')
                    response = response.data
                    console.log(response)
                    var all = response.win + response.loss + response.break;
                    var wins = Math.round(response.win/all *100)
                    var losses = Math.round(response.loss  /all *100)
                    var breakevens = Math.round(response.break/all *100)
                    
                    vm.datasetWinLoss.datasets[0].data = [wins,losses,breakevens]
                    vm.datasetWinLoss.loaded = true
                });
            },
            loadDataLongShort() {
                var vm = this
                axios.get('/chart-longshort').then((response) => {
                    console.log('loadDataLongShort')
                    response = response.data
                    console.log(response)
                    var all = (response.long + response.short)
                    var long = Math.round(response.long/all *100)
                    var short = Math.round(response.short/all *100)
                    
                    vm.datasetLongShort.datasets[0].data = [long,short]
                    vm.datasetLongShort.loaded = true
                });
            },
            loadNext() {
                var vm = this
                setTimeout(function() {
                    vm.$refs.radarChart.update()
                    vm.$refs.upDownChart.update()
                    vm.$refs.longShortChart.update()
                    // vm.$refs.perHoursChart.update()
                }, 5000)
            }
    },

    mounted: function() {
        this.loadDataSumDiary()
        this.loadDataWinLoss()
        this.loadDataLongShort()
    }
  }
</script>