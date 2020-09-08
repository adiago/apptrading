<style>
    .pa-15 {
        padding: 15px;
    }
    .static-position {
        position: static;
    }
</style>
<template>
    <div class="card static-position">
        <div class="card-header">
            Lasts transactions
        </div>
        <div class="card-body">
            <div class="form-group row ml-3">
                <label for="inputState" class="col-form-label">Mostrar</label>
                <div class="col-md-2 col-sm-3 col-xs-6">
                    <select id="inputState" v-model="qty" class="form-control">
                        <option v-for="n in arrQty">{{n}}</option>
                    </select>
                </div>
            </div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col" v-for="(col,idx) in columns" :key="idx">{{col}}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="data in datatable" :key="data.id">
                    <td>{{data.date}}</td>
                    <td>{{data.side_name}}</td>
                    <td>{{data.market_name}}</td>
                    <td>{{data.asset_name}}</td>
                    <td>{{data.strategy_name || 'None'}}</td>
                    <td :class="colorBalance(data.entry_price, data.exit_price, data.side_id)">{{balance(data.entry_price, data.exit_price, data.side_id)}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                columns: ['Date', 'Side', 'Market', 'Asset', 'Strategy', 'Balance'],
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

            balance(entry,exit,side) {
                let value = 0
                if (side == this.sellCode) {
                    value = entry-exit
                } else {
                    value = exit-entry
                }
                return value.toFixed(2)
            },
            colorBalance(entry,exit,side) {
                let balance = this.balance(entry,exit,side)
                if(balance>0) {
                    return 'text-success'
                } else if(balance < 0) {
                    return 'text-danger'
                } else {
                    return ''
                }
            },
            changeQty(qty) {
                this.$emit('qty', qty)
            }
        }
    }
</script>