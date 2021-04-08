<style>
.center-card {
    margin-left: auto ;
    margin-right: auto ;
}
</style>

<template>
    <div class="py-3">
        <div class="card my-3 static-position">
            <div class="card-header">Sumatorio Diario</div>
            <div class="card-body">
                <linechart ref="radarChart" v-if="datasetWinLoss.loaded" :chart-data="datasetSumDiary"></linechart>
            </div>
        </div>

         <div class="card my-3 static-position">
            <div class="card-header">Operaciones Positivas/Negativas</div>
            <div class="card-body center-card">
                <piechart ref="radarChart" v-if="datasetSumDiary.loaded" :chart-data="datasetWinLoss"></piechart>
            </div>
        </div>
    </div>
</template>

<script>
import PieChart from "./PieChart.vue"


  export default {
    components: { PieChart },
    name: 'VueChartJS',
    data: () => ({
        
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
            loadDataWinLoss(data) {
                var vm = this
                var all = data.countTrades
                var wins = Math.round(data.winTrades/all *100)
                var losses = Math.round(data.lossTrades/all *100)
                var breakevens = Math.round(data.breakEven/all *100)
                
                vm.datasetWinLoss.datasets[0].data = [wins,losses,breakevens]
                vm.datasetWinLoss.loaded = true
            },
            loadNext() {
                var vm = this
                setTimeout(function() {
                    vm.$refs.radarChart.update()
                    vm.$refs.radarChart.update()
                }, 5000)
            }
    },

    mounted: function() {
        this.loadDataSumDiary()
    }
  }
</script>