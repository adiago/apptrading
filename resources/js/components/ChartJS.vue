<template>
    <div class="py-5">
        <linechart ref="radarChart" v-if="loaded" :chart-data="datacollection"></linechart>
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
                    backgroundColor: '#249EBF',
                    pointBackgroundColor: 'white',
                    borderWidth: 1,
                    pointBorderColor: '#249EBF',
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
                        vm.datacollection.labels = Object.keys(response.data)
                        vm.datacollection.datasets[0].data = Object.values(response.data)
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