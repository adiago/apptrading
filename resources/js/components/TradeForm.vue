<template id="bs-modal">
    <!-- MODAL -->
    <div class="modal fade" id="addTradeModal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{actionTitle}} trade</h5>
                    <button type="button" class="close" data-dismiss="modal" @click="resetForm">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputmarket">Market</label>
                                <select id="inputmarket"
                                        class="form-control"
                                        v-model="market"
                                        required>
                                    <option disabled value='null'>Select market</option>
                                    <option v-for="n in arrMarket" :value="n.id">{{n.name}}</option>
                                </select>
                            </div>
                            <fieldset class="form-group col-md-6" :disabled="market==null">
                                <label for="inputasset">Asset</label>
                                <select v-model="asset"
                                        id="inputasset"
                                        class="form-control"
                                >
                                    <option  disabled value='null'>Select asset</option>
                                    <option v-for="n in arrAsset" :value="n.id"> {{n.name}}</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputside">Side</label>
                                <select id="inputside" class="form-control" v-model="side">
                                    <option  disabled value='null'>Select side</option>
                                    <option v-for="n in arrSide" :value="n.id">{{n.name}}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputtradedate">Date</label>
                                <input type="datetime-local" id="inputtradedate" class="form-control" v-model="tradedate">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputentryprice">Entry Price</label>
                                <input  v-model="entryprice"
                                        type="number"
                                        step="0.01"
                                        class="form-control"
                                        id="inputentryprice"
                                        required
                                >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputexitprice">Exit Price</label>
                                <input v-model="exitprice"
                                       type="number"
                                       step="0.01"
                                       class="form-control"
                                       id="inputexitprice"
                                       required
                                >
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputstrategy">Trade Strategy</label>
                                <select id="inputstrategy" class="form-control" v-model="strategy">
                                    <option disabled value='null'>None</option>
                                    <option v-for="n in arrStrategy" :value="n.id">{{n.name}}</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer form-group">
                    <button type="button" class="btn btn-secondary" @click="resetForm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" @click="saveForm()">Save trade</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

    export default {
        data() {
            return {
                arrMarket: [],
                market: null,
                allAssets: [],
                arrAsset: [],
                asset: null,
                arrSide: [],
                side: null,
                tradedate: null,
                entryprice: null,
                exitprice: null,
                arrStrategy: [],
                strategy: null,
                update: 0,
                tradeId: null,
                actionTitle: 'Add'
            }
        },
        props: ['userId'],

        computed: {
            transaction() {
                    return this.$store.state.transaction
                }
        },

        methods: {
            loadData() {
                var vm = this
                vm.loadMarkets()
                vm.loadAssets()
                vm.loadSides();
                vm.loadStrategies();
            },
            loadMarkets() {
                let vm = this
                axios.get('/markets')
                    .then((response) => {
                        vm.arrMarket = response.data
                    });
            },
            loadAssets() {
                let vm = this
                axios.get('/assets')
                    .then((response) => {
                        vm.allAssets = response.data
                    });
            },
            loadSides() {
                let vm = this
                axios.get('/sides')
                    .then((response) => {
                        vm.arrSide = response.data
                    });
            },
            loadStrategies() {
                let vm = this
                axios.get('/strategies')
                    .then((response) => {
                        vm.arrStrategy = response.data
                    });
            },

            saveForm() {
                let validate = this.validateForm()
                if (!validate) return null
                let vm = this
                let data = {
                    'side_id': vm.side,
                    'strategy_id': vm.strategy,
                    'asset_id': vm.asset,
                    'entry_price': vm.entryprice,
                    'exit_price': vm.exitprice,
                    'trade_date': vm.tradedate,
                    'user_id': vm.userId
                }

                if (this.tradeId == null) {
                    axios.post('/trades', data)
                        .then((response) => {
                            // handle success
                            $('#addTradeModal').modal('hide');
                            this.$emit('tradeForm', {message: 'created!', status: 'ok'})
                        })
                        .catch((error) => {
                            // handle error
                            $('#addTradeModal').modal('hide');
                            this.$emit('tradeForm', {status: 'ko'})
                        })
                        .then(()=> {
                            // always executedt
                            console.log('always there');
                        });
                } else {
                    axios.put('/trades/'+this.tradeId, data)
                        .then((response) => {
                            // handle success
                            $('#addTradeModal').modal('hide');
                            this.$emit('tradeForm', {message:'updated!', status: 'ok'})
                        })
                        .catch((error) => {
                            // handle error
                            $('#addTradeModal').modal('hide');
                            this.$emit('tradeForm', {status: 'ko'})
                        })
                        .then(() => {
                            // always executedt
                            console.log('always there');
                        });
                }
                this.resetForm()
            },

            validateForm() {
                $('#inputmarket').css({"color":"#495057"})
                let validate = true
                let fields = ['side', 'asset', 'market', 'entryprice', 'exitprice', 'tradedate']
                let errors = []

                fields.forEach(field => {
                    $('#input'+field).css({"color": "#495057", "border":"1px solid #ced4da"})
                    if(this[field] == null) {
                        validate = false
                        errors.push('input'+field)
                    }
                })

                errors.forEach(error => {
                    $('#'+error).css({"color": "red", "border":"red solid 1px"})
                })
                return validate
            },
            resetForm() {
                let vm = this
                vm.market = null,
                vm.arrAsset = [],
                vm.asset = null,
                vm.side = null,
                vm.tradedate = null,
                vm.entryprice = null,
                vm.exitprice = null,
                vm.strategy = null,
                vm.update = false
                vm.actionTitle = 'Add'
                vm.tradeId = null
                
                this.$store.commit("updateTransaction", {})
            }
        },

        watch: {
            market: function (val, oldVal) {
                if (val != oldVal && val !== null) {
                    this.arrAsset = this.allAssets.filter(function (asset) {
                        return asset.market_id === val
                    })
                    if(!this.update)
                        this.asset = null;
                }
            },
            transaction: function(val, oldVal) {
                if(val.data === undefined) return
                let transaction = val.data
                let date_transaction = transaction.date

                this.update = 1
                this.market = transaction.market_id
                this.asset = transaction.asset_id
                this.strategy = transaction.strategy_id
                this.side = transaction.side_id
                this.entryprice = transaction.entry_price
                this.exitprice = transaction.exit_price
                this.tradedate = date_transaction.replace(" ", "T").replace(/...$/,"")
                this.tradeId = transaction.trade_id
            },
            update: function(val, oldVal) {
                if((val != oldVal) && val ) {
                    this.actionTitle = 'Edit'
                }
            }
        },
        mounted: function () {
            this.loadData()
        }
    }
</script>