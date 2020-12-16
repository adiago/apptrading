<template>
    <div class="py-3">
        <div class="card my-3">
            <div class="card-header">Sumatorio Diario</div>
            <div class="card-body">
                <linechart ref="radarChart" v-if="loaded" :chart-data="datacollection"></linechart>
            </div>
        </div>

        <!-- <div class="card my-3">
            <div class="card-header">Puntos acumulados</div>
            <div class="card-body">
                <linechart ref="radarChart" v-if="loaded" :chart-data="datacollection"></linechart>
            </div>
        </div> -->
    </div>
</template>

<script>

  export default {
    name: 'VueChartJS',
    data: () => ({
        loaded: false,
        datacollection: {
            // title: 'Puntos',
            labels: [],
            datasets: [
                {
                    label: 'Puntos',
                    lineTension: 0, 
                    fill: false,
                    // backgroundColor: '#249EBF',
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
            loadData() {
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

                        vm.datacollection.labels = Object.keys(ordered)
                        vm.datacollection.datasets[0].data = Object.values(ordered)
                        vm.loaded = true
                    });

            },
            loadNext() {
                var vm = this
                setTimeout(function() {
                    // vm.datacollection.datasets[0].data = [1,2,3,4,5,6,7,8,9,10,11,12]
                    vm.$refs.radarChart.update()


                }, 5000)
            }
    },

    mounted: function() {
        this.loadData()
        // this.loadNext()
    }
  }
</script>