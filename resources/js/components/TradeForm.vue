<template id="bs-modal">
    <!-- MODAL -->
    <div class="modal fade" id="addTradeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="material-icons me-2">add_circle</i>{{actionTitle}} Trade
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" @click="resetForm" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="saveForm()">
                        <!-- Market and Asset Row -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="inputmarket" class="form-label fw-bold">
                                    <i class="material-icons md-18 align-middle">store</i> Market <span class="text-danger">*</span>
                                </label>
                                <select id="inputmarket"
                                        class="form-select form-select-lg"
                                        v-model="market"
                                        required>
                                    <option disabled value='null'>Select market</option>
                                    <option v-for="n in arrMarket" :value="n.id">{{n.name}}</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" :class="{'opacity-50': market==null}">
                                <label for="inputasset" class="form-label fw-bold">
                                    <i class="material-icons md-18 align-middle">list</i> Asset <span class="text-danger">*</span>
                                </label>
                                <div class="position-relative">
                                    <input type="text" 
                                           id="assetSearch"
                                           class="form-control form-control-lg"
                                           placeholder="Search asset..."
                                           v-model="assetSearch"
                                           @focus="showAssetDropdown = true"
                                           @input="showAssetDropdown = true"
                                           @keydown.enter.prevent="selectFirstAsset()"
                                           :disabled="market==null"
                                           autocomplete="off">
                                    <div v-if="showAssetDropdown && filteredAssets.length > 0" 
                                         class="dropdown-menu show w-100 position-absolute" 
                                         style="max-height: 200px; overflow-y: auto; z-index: 1050;">
                                        <a v-for="assetItem in filteredAssets" 
                                           :key="assetItem.id"
                                           class="dropdown-item" 
                                           href="#"
                                           @click.prevent="selectAsset(assetItem)">
                                            {{assetItem.name}}
                                        </a>
                                    </div>
                                    <div v-if="asset && selectedAssetName" class="form-text">
                                        Selected: <strong>{{selectedAssetName}}</strong>
                                    </div>
                                </div>
                                <input type="hidden" id="inputasset" v-model="asset">
                            </div>
                        </div>

                        <!-- Side and Date Row -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold d-block">
                                    <i class="material-icons md-18 align-middle">swap_horiz</i> Side <span class="text-danger">*</span>
                                </label>
                                <div class="btn-group w-100" role="group">
                                    <button type="button" 
                                            class="btn btn-lg"
                                            :class="side === buySideId ? 'btn-success' : 'btn-outline-success'"
                                            @click="side = buySideId"
                                            :disabled="arrSide.length === 0">
                                        <i class="material-icons md-18 align-middle">trending_up</i> Buy
                                    </button>
                                    <button type="button" 
                                            class="btn btn-lg"
                                            :class="side === sellSideId ? 'btn-danger' : 'btn-outline-danger'"
                                            @click="side = sellSideId"
                                            :disabled="arrSide.length === 0">
                                        <i class="material-icons md-18 align-middle">trending_down</i> Sell
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="inputtradedate" class="form-label fw-bold">
                                    <i class="material-icons md-18 align-middle">event</i> Date & Time <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local" 
                                       id="inputtradedate" 
                                       class="form-control form-control-lg" 
                                       v-model="tradedate"
                                       required>
                            </div>
                        </div>

                        <!-- Prices Row -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <label for="inputentryprice" class="form-label fw-bold">
                                    <i class="material-icons md-18 align-middle">arrow_upward</i> Entry Price <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">$</span>
                                    <input v-model="entryprice"
                                           type="number"
                                           step="0.01"
                                           class="form-control"
                                           id="inputentryprice"
                                           placeholder="0.00"
                                           required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="inputexitprice" class="form-label fw-bold">
                                    <i class="material-icons md-18 align-middle">arrow_downward</i> Exit Price <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">$</span>
                                    <input v-model="exitprice"
                                           type="number"
                                           step="0.01"
                                           class="form-control"
                                           id="inputexitprice"
                                           placeholder="0.00"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Strategy Row -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="inputstrategy" class="form-label fw-bold">
                                    <i class="material-icons md-18 align-middle">psychology</i> Strategy
                                </label>
                                <select id="inputstrategy" 
                                        class="form-select form-select-lg" 
                                        v-model="strategy">
                                    <option :value="null">None</option>
                                    <option v-for="n in arrStrategy" :value="n.id">{{n.name}}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Profit/Loss Preview -->
                        <div v-if="entryprice && exitprice" class="alert" :class="calculateProfitLoss() >= 0 ? 'alert-success' : 'alert-danger'">
                            <strong>
                                <i class="material-icons md-18 align-middle">{{calculateProfitLoss() >= 0 ? 'check_circle' : 'cancel'}}</i>
                                {{calculateProfitLoss() >= 0 ? 'Profit' : 'Loss'}}: 
                                ${{calculateProfitLoss().toFixed(2)}}
                            </strong>
                            <span v-if="entryprice" class="text-muted ms-2">
                                ({{((calculateProfitLoss() / entryprice) * 100).toFixed(2)}}%)
                            </span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-lg" @click="resetForm" data-bs-dismiss="modal">
                        <i class="material-icons md-18 align-middle">close</i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" @click="saveForm()">
                        <i class="material-icons md-18 align-middle">save</i> Save Trade
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.modal-lg {
    max-width: 800px;
}
.material-icons.md-18 {
    font-size: 18px;
    vertical-align: middle;
}
.btn-group .btn {
    transition: all 0.3s ease;
}
.dropdown-menu {
    top: 100%;
    margin-top: 0.125rem;
    border: 1px solid #dee2e6;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
.dropdown-item:hover {
    background-color: #f8f9fa;
}
.form-control.is-invalid,
.form-select.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6 .4.4.4-.4m0 4.8-.4-.4-.4.4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
.form-control.is-valid,
.form-select.is-valid {
    border-color: #198754;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
</style>
<script>

    export default {
        data() {
            return {
                arrMarket: [],
                market: null,
                allAssets: [],
                arrAsset: [],
                asset: null,
                assetSearch: '',
                showAssetDropdown: false,
                selectedAssetName: '',
                arrSide: [],
                side: null,
                buySideId: null,
                sellSideId: null,
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
            },
            filteredAssets() {
                if (!this.assetSearch || this.assetSearch.trim() === '') {
                    return this.arrAsset.slice(0, 10); // Mostrar primeros 10 si no hay búsqueda
                }
                const search = this.assetSearch.toLowerCase();
                return this.arrAsset.filter(asset => 
                    asset.name.toLowerCase().includes(search)
                ).slice(0, 20); // Limitar a 20 resultados
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
                        // Identificar IDs de Buy y Sell
                        const buySide = response.data.find(s => s.name.toLowerCase() === 'buy' || s.id === 1);
                        const sellSide = response.data.find(s => s.name.toLowerCase() === 'sell' || s.id === 2);
                        if (buySide) vm.buySideId = buySide.id;
                        if (sellSide) vm.sellSideId = sellSide.id;
                    });
            },
            selectAsset(assetItem) {
                this.asset = assetItem.id;
                this.selectedAssetName = assetItem.name;
                this.assetSearch = assetItem.name;
                this.showAssetDropdown = false;
            },
            selectFirstAsset() {
                if (this.filteredAssets.length > 0) {
                    this.selectAsset(this.filteredAssets[0]);
                }
            },
            calculateProfitLoss() {
                if (!this.entryprice || !this.exitprice) return 0;
                const entry = parseFloat(this.entryprice);
                const exit = parseFloat(this.exitprice);
                if (this.side === this.buySideId) {
                    // Buy: profit = exit - entry
                    return exit - entry;
                } else if (this.side === this.sellSideId) {
                    // Sell: profit = entry - exit
                    return entry - exit;
                }
                return exit - entry; // Default
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

                // Bootstrap 5 modal API
                const modalElement = document.getElementById('addTradeModal');
                const modalInstance = window.bootstrap ? window.bootstrap.Modal.getInstance(modalElement) : null;
                const modal = modalInstance || (window.bootstrap ? new window.bootstrap.Modal(modalElement) : null);

                if (this.tradeId == null) {
                    axios.post('/trades', data)
                        .then((response) => {
                            // handle success
                            if (modal) modal.hide();
                            this.$emit('tradeForm', {message: 'created!', status: 'ok'})
                        })
                        .catch((error) => {
                            // handle error
                            if (modal) modal.hide();
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
                            if (modal) modal.hide();
                            this.$emit('tradeForm', {message:'updated!', status: 'ok'})
                        })
                        .catch((error) => {
                            // handle error
                            if (modal) modal.hide();
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
                let validate = true
                let errors = []
                
                // Validar campos requeridos
                const requiredFields = {
                    'market': 'inputmarket',
                    'asset': 'inputasset',
                    'entryprice': 'inputentryprice',
                    'exitprice': 'inputexitprice',
                    'tradedate': 'inputtradedate'
                }

                Object.keys(requiredFields).forEach(field => {
                    const elementId = requiredFields[field]
                    const element = document.getElementById(elementId)
                    
                    if (element) {
                        element.classList.remove('is-invalid', 'border-danger', 'is-valid')
                        
                        if (this[field] == null || this[field] === 'null' || this[field] === '') {
                            validate = false
                            errors.push(elementId)
                            element.classList.add('is-invalid', 'border-danger')
                        } else {
                            element.classList.add('is-valid')
                        }
                    }
                })
                
                // Validar side (botones)
                const sideButtons = document.querySelectorAll('.btn-group button[type="button"]');
                if (this.side == null || this.side === 'null' || this.side === '') {
                    validate = false;
                    sideButtons.forEach(btn => {
                        btn.classList.add('border-danger');
                    });
                } else {
                    sideButtons.forEach(btn => {
                        btn.classList.remove('border-danger');
                    });
                }

                return validate
            },
            resetForm() {
                let vm = this
                vm.market = null
                vm.arrAsset = []
                vm.asset = null
                vm.assetSearch = ''
                vm.selectedAssetName = ''
                vm.showAssetDropdown = false
                vm.side = null
                vm.tradedate = null
                vm.entryprice = null
                vm.exitprice = null
                vm.strategy = null
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
                    if(!this.update) {
                        this.asset = null;
                        this.assetSearch = '';
                        this.selectedAssetName = '';
                    }
                }
            },
            asset: function (val) {
                if (val) {
                    const selected = this.arrAsset.find(a => a.id === val);
                    if (selected) {
                        this.selectedAssetName = selected.name;
                        this.assetSearch = selected.name;
                    }
                }
            },
            transaction: function(val, oldVal) {
                if(val.data === undefined) return
                let transaction = val.data
                let date_transaction = transaction.date

                this.update = 1
                this.market = transaction.market_id
                // Asegurar que los assets se cargan primero
                this.$nextTick(() => {
                    this.asset = transaction.asset_id
                })
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
            
            // Cerrar dropdown cuando se hace clic fuera
            document.addEventListener('click', (e) => {
                const assetSearch = document.getElementById('assetSearch');
                const dropdown = assetSearch?.parentElement?.querySelector('.dropdown-menu');
                
                if (assetSearch && dropdown && !assetSearch.contains(e.target) && !dropdown.contains(e.target)) {
                    this.showAssetDropdown = false;
                }
            });
            
            // Cerrar dropdown al presionar Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.showAssetDropdown = false;
                }
            });
        }
    }
</script>