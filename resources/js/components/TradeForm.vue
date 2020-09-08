<template id="bs-modal">
    <!-- MODAL -->
    <div class="modal fade" id="addTradeModal" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add trade</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
            }
        },
        props: ['userId'],

        methods: {
            loadData() {
                this.loadMarkets()
                this.loadAssets()
                this.loadSides();
                this.loadStrategies();
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
                    'side_id': this.side,
                    'strategy_id': this.strategy,
                    'asset_id': this.asset,
                    'entry_price': this.entryprice,
                    'exit_price': this.exitprice,
                    'trade_date': this.tradedate,
                    'user_id': this.userId
                }
                axios.post('/trades', data)
                    .then((response) => {
                        // handle success
                        $('#addTradeModal').modal('hide');
                        this.$emit('tradeForm', 'ok')
                    })
                    .catch((error) => {
                        // handle error
                        $('#addTradeModal').modal('hide');
                        this.$emit('tradeForm', 'ko')
                    })
                    .then(() => {
                        // always executedt
                        console.log('always there');
                    });
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
        },

        watch: {
            market: function (val, oldVal) {
                if (val != oldVal) {
                    this.arrAsset = this.allAssets.filter(function (asset) {
                        return asset.market_id === val
                    })
                    this.asset = null;
                }
            }
        },
        mounted: function () {
            this.loadData()
        }
    }
</script>