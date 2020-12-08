<style>
@media screen and (max-width: 767px) {
    .widget-custom {
        min-width: 50%!important;
    }
}
</style>
<template>
    <div class="row">
    <!--<div class="row wrap col-md-12 justify-content-around">-->
        <div class="h-100 col-md-4 col-sm-6 col-xs-6 w-auto widget-custom" v-for="(w,index) in widgets" :key="index">
            <div class="card text-white" :class="w.color">
                <div class="card-body">
                    <div class="rotate">
                        <i class="fa fa-user fa-4x"></i>
                    </div>
                    <h6 class="text-uppercase">
                        <i class="material-icons">poll</i>
                        {{w.name}}
                    </h6>
                    <div  v-show="loadwidget" class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <h1 v-show="!loadwidget" class="display-5">{{w.val}}</h1>
                </div>
            </div>
        </div>
    <!--</div>-->
    </div>
</template>
<script>
    export default {
        data() {
            return {
                loadwidget: false,
                widgets: [
                    { id: 1, name: 'Trades', val: 0, color: 'bg-gradient-info' },
                    { id: 2, name: 'Points', val: 0, color: 'bg-gradient-secondary' },
                    { id: 3, name: 'Ratio B/R', val: 0, color: 'bg-gradient-warning' },
                    { id: 4, name: 'Win Trades', val: 0, color: 'bg-gradient-success' },
                    { id: 5, name: 'Break Even', val: 0, color: 'bg-gradient-dark' },
                    { id: 6, name: 'Loss Trades', val: 0, color: 'bg-gradient-danger' }
                ],
            }
        },
        props: ['userId'],

        methods: {
            loadWidgetStats(widgetsData) {
                this.widgets[0].val = widgetsData.countTrades
                this.widgets[1].val = widgetsData.pointsTrades
                this.widgets[3].val = widgetsData.winTrades
                this.widgets[5].val = widgetsData.lossTrades
                this.widgets[4].val = widgetsData.breakEven
                this.loadwidget = false;
            },
        },
        mounted: function() {
            this.loadwidget = true;
        }
    };
</script>