<style>
</style>

<template>
    <div>
        hello
        {{hola}}
        <canvas id="chart1" width="800" height="400"></canvas>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        props: [],

        methods: {
            chart1() {
                var stars = [135850, 52122, 148825, 16939, 9763];
                var frameworks = ['React', 'Angular', 'Vue', 'Hyperapp', 'Omi'];

                var ctx = document.getElementById('chart1');

                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: frameworks,
                        datasets: [{
                            label: 'Popular JavaScript Frameworks',
                            data: stars
                        }]
                    }
                })
            }
        },
        mounted: function() {
        }
    };
</script>