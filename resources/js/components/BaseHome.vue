<style scoped>
    .float{
        position:fixed;
        width:60px;
        height:60px;
        bottom:40px;
        right:40px;
        background-color:#0C9;
        color:#FFF;
        border-radius:50px;
        text-align:center;
        box-shadow: 2px 2px 3px #999;
    }
    .my-float{
        margin-top:5px;
        color: white;
    }
</style>
<template>
    <div>
        <widget :user-id=this.userId :widgetsData=this.widgetsData ref="widget"></widget>
        <trade-form
            :user-id=this.userId
            @tradeForm="tradeFormSaved">
        </trade-form>
        <a
            data-toggle="modal" data-target="#addTradeModal"
            class="float btn btn-success">
            <i class="material-icons md-36 my-float">add</i>
        </a>
        <trade-list ref="tradelist" @qty="loadDataTable"></trade-list>

        <chartjs></chartjs>
        <!-- TOAST -->
        <toast-message
                :icon="this.toastIcon"
                :header="this.toastHeader"
                :message="this.toastMessage"
                v-show="this.showToast"
        ></toast-message>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                toastIcon: null,
                toastHeader: null,
                toastMessage: null,
                showToast: false,
                widgetsData: [],
            }
        },
        props: ['userId'],

        methods: {
            loadWidgetStats() {
                axios.get('/widgets-stats')
                    .then((response) => {
                        this.$refs.widget.loadWidgetStats(response.data)
                    });
            },
            loadDataTable(qty=10) {
                let query='?qty='+qty
                axios.get('/data-tables'+query)
                    .then((response) => {
                        this.$refs.tradelist.loadData(response.data)
                    });
            },
            tradeFormSaved(val) {
                if(val=='ok') {
                    this.toastIcon = 'mood'
                    this.toastHeader = 'Trade added!'
                    this.toastMessage = 'The trade has been successfully added.'
                    this.updateData()
                } else if (val == 'ko') {
                    this.toastIcon = 'mood_bad'
                    this.toastHeader = 'Trade failed!'
                    this.toastMessage = 'The trade could not be added.'
                }
                this.showToast = true;
                setTimeout(function() {
                    $('.toast').toast('show')
                }, 1000);
            },
            updateData() {
                this.loadWidgetStats()
                this.loadDataTable()
            },

        },
        mounted: function() {
            this.updateData()
        }
    }
</script>