<style>
.center-card {
    margin-left: auto ;
    margin-right: auto ;
}
.empty-state {
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
</style>

<template>
    <div class="row py-3">
        <div class="col-sm-12 pa-15 static-position">
            <div class="card my-3 pa-0">
                <div class="card-header">Sumatorio Diario</div>
                <div class="card-body">
                    <div v-if="!datasetSumDiary.loaded || !hasSumDiaryData" class="text-center py-5 empty-state">
                        <i class="material-icons" style="font-size: 80px; color: #6c757d; opacity: 0.5;">show_chart</i>
                        <h5 class="mt-3 text-muted">No data available</h5>
                        <p class="text-muted">Add some trades to see your daily summary chart.</p>
                    </div>
                    <linechart v-else ref="radarChart" :chart-data="datasetSumDiary"></linechart>
                </div>
            </div>
        </div>
        <div class="col-sm-12 pa-15 static-position">
            <div class="card my-3 pa-0">
                <div class="card-header">Operaciones Positivas/Negativas</div>
                <div class="card-body center-card">
                    <div v-if="!datasetWinLoss.loaded || !hasWinLossData" class="text-center py-5 empty-state">
                        <i class="material-icons" style="font-size: 80px; color: #6c757d; opacity: 0.5;">pie_chart</i>
                        <h5 class="mt-3 text-muted">No data available</h5>
                        <p class="text-muted">Complete some trades to see win/loss statistics.</p>
                    </div>
                    <piechart v-else ref="upDownChart" :chart-data="datasetWinLoss"></piechart>
                </div>
            </div>
        </div>
        <div class="col-sm-12 pa-15 static-position">
            <div class="card my-3 pa-0">
                <div class="card-header">Operaciones Largo/Corto</div>
                <div class="card-body center-card">
                    <div v-if="!datasetLongShort.loaded || !hasLongShortData" class="text-center py-5 empty-state">
                        <i class="material-icons" style="font-size: 80px; color: #6c757d; opacity: 0.5;">donut_large</i>
                        <h5 class="mt-3 text-muted">No data available</h5>
                        <p class="text-muted">Add buy and sell trades to see long/short distribution.</p>
                    </div>
                    <piechart v-else ref="longShortChart" :chart-data="datasetLongShort"></piechart>
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
    computed: {
        hasSumDiaryData() {
            return this.datasetSumDiary.labels && this.datasetSumDiary.labels.length > 0 && 
                   this.datasetSumDiary.datasets[0].data && this.datasetSumDiary.datasets[0].data.length > 0;
        },
        hasWinLossData() {
            return this.datasetWinLoss.datasets[0].data && 
                   this.datasetWinLoss.datasets[0].data.some(val => val > 0);
        },
        hasLongShortData() {
            return this.datasetLongShort.datasets[0].data && 
                   this.datasetLongShort.datasets[0].data.some(val => val > 0);
        }
    },
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
                        
                        if (!objs || Object.keys(objs).length === 0) {
                            vm.datasetSumDiary.labels = []
                            vm.datasetSumDiary.datasets[0].data = []
                            vm.datasetSumDiary.loaded = true
                            return
                        }
                        
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
                    }).catch(() => {
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
                    
                    if (all === 0) {
                        vm.datasetWinLoss.datasets[0].data = [0, 0, 0]
                        vm.datasetWinLoss.loaded = true
                        return
                    }
                    
                    var wins = Math.round(response.win/all *100)
                    var losses = Math.round(response.loss  /all *100)
                    var breakevens = Math.round(response.break/all *100)
                    
                    vm.datasetWinLoss.datasets[0].data = [wins,losses,breakevens]
                    vm.datasetWinLoss.loaded = true
                }).catch(() => {
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
                    
                    if (all === 0) {
                        vm.datasetLongShort.datasets[0].data = [0, 0]
                        vm.datasetLongShort.loaded = true
                        return
                    }
                    
                    var long = Math.round(response.long/all *100)
                    var short = Math.round(response.short/all *100)
                    
                    vm.datasetLongShort.datasets[0].data = [long,short]
                    vm.datasetLongShort.loaded = true
                }).catch(() => {
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