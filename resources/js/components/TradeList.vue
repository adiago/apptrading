<style>
    .pa-15 {
        padding: 15px;
    }
    .pa-0 {
        padding: 0px!important;
    }
    .static-position {
        position: static;
    }
</style>
<template>
<!-- https://es.investing.com/webmaster-tools/profit-calculator -->
    <div class="row">
        <div class="col-sm card static-position my-3 pa-0">
            <div class="card-header">
                Latest transactions
            </div>
            <div class="card-body">
                <div class="form-group row ml-3">
                    <label for="inputState" class="col-form-label">Mostrar</label>
                    <div class="col-md-2 col-sm-3 col-xs-6">
                        <select id="inputState" v-model="qty" class="form-control">
                            <option v-for="(n,idx) in arrQty" :key="idx">{{n}}</option>
                        </select>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col" :class="(col=='Strategy') ? 'd-none d-sm-block' : ''" v-for="(col,idx) in columns" :key="idx">{{col}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="data in datatable" :key="data.id">
                        <td>{{data.date}}</td>
                        <td>{{data.side_name}}</td>
                        <!-- <td class="hidden-xs-down">{{data.market_name}}</td> -->
                        <td>{{data.asset_name}}</td>
                        <td class="d-none d-sm-block">{{data.strategy_name || 'None'}}</td>
                        <td :class="colorBalance(data.points)">{{data.points}}</td>
                        <td>
                            <a data-toggle="modal" data-target="#addTradeModal" @click="sendTransaction(data)">
                                <i class="material-icons pointer hover-darkred" title="Editar">edit</i>
                            </a>
                            <!-- <a data-toggle="modal" data-target="#addTradeModal" @click="deleteTransaction(data)">
                                <i class="material-icons pointer hover-darkred">delete</i>
                            </a> -->
        
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                columns: ['Date', 'Side',  'Asset', 'Strategy', 'Points', 'Actions'],
                datatable: [],
                qty: 10,
                arrQty: ['3','5','10'],
                buyCode: 1,
                sellCode: 2
            }
        },

        watch: {
            qty: function(val) {
                this.changeQty(val)
            }
        },
        methods: {
            loadData(data) {
                this.datatable = data
            },

            format(value) {
                return value.toFixed(2)
            },
            balance(entry,exit,side) {
                let value = 0
                if (side == this.sellCode) {
                    value = entry-exit
                } else {
                    value = exit-entry
                }
                return value.toFixed(2)
            },
            colorBalance(value) {
                // let balance = this.balance(entry,exit,side)
                if(value>0) {
                    return 'text-success'
                } else if(value < 0) {
                    return 'text-danger'
                } else {
                    return ''
                }
            },
            changeQty(qty) {
                this.$emit('qty', qty)
            },
            sendTransaction(data) {
                this.$store.commit("updateTransaction", {data})
            }
        }
    }
</script>