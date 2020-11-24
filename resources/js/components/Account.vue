<style scoped>
    .my-float{
            margin-top:5px;
            color: white;
        }
        .w-10-percent {
            width: 10%;
        }
</style>
<template>
    <div class="container">
        <div class="card m-4">
            <div class="card-header">
                Perfil
            </div>
            <div class="card-body">
                <div class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <label for="user_name">Nombre</label>
                        <input  v-model="user.name"
                                type="text"
                                class="form-control"
                                id="user_name"
                                v-on:keyup.enter="editUser(user.id)"
                                required
                        >
                    </div>
                    <div class="form-group col-md-6">
                        <label for="user_email">E-mail</label>
                        <input  v-model="user.email"
                                type="email"
                                class="form-control"
                                id="user_email"
                                v-on:keyup.enter="editUser(user.id)"
                                required
                        >
                    </div>
                </div>
                <button type="submit" class="btn btn-primary float-right" @click="editUser(user.id)">Guardar</button>
            </div>
        </div>
        <div class="card m-4">
            <div class="card-header">
                Estrategias
            </div>
            <div class="card-body">
                <div class="form-row col-md-12">
                    <div class="form-group col-md-6">
                        <div class="form-row col-md-12">
                            <label for="strategy_name">Nueva estrategia</label>
                        </div>
                        <div class="form-row col-md-12">
                            <div class="form-group col-md-8">
                                <input  v-model="newNameStrategy"
                                    type="text"
                                    class="form-control"
                                    id="strategy_name"
                                    v-on:keyup.enter="saveNewStrategy()"
                                    required
                                >
                            </div>
                            <div class="form-group pl-1">
                                <button type="submit" class="btn btn-sm btn-primary" @click="saveNewStrategy()"><i class="material-icons md-18 my-float">add</i></button>
                            </div>
                        </div>
                    </div>
                   
                    <div class="form-group col-md-6">
                        Mis estrategias<br>
                        <div>
                            <table class="table table-striped">
                                <tr v-for="strategy in strategies" :key="strategy.id">
                                    <td><input class="form-control-plaintext" @blur="editStrategy(strategy)" :ref="strategy.id" v-model="strategy.name"/></td>
                                    <td class="w-10-percent"><i class="material-icons float-right pointer hover-darkred" @click="focusInput(strategy.id)">edit</i></td>
                                    <td class="w-10-percent"><i class="material-icons float-right pointer hover-darkred" @click="deleteStrategy(strategy)">delete</i></td>
                                </tr>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                strategies: [],
                newNameStrategy: null,
                newdDescriptionStategy: null,

                toastIcon: null,
                toastHeader: null,
                toastMessage: null,
                showToast: false,
            }
        },
        props: ['user'],
        methods: {
            loadUser() {
                axios.get('/ownUser').then((response) => {
                    console.log(response);
                });
            },
            loadStrategies() {
                axios.get('/strategies')
                    .then((response) => {
                        this.strategies = response.data
                    });
            },

            focusInput(id) {
                let input = this.$refs[id][0];
                input.focus()
            },

            editStrategy(strategy) {
                let vm = this
                let data = {
                    'name': strategy.name,
                    'description': strategy.name.description,
                    'user_id': this.user.id
                }
                axios.put('/strategies/'+strategy.id, data)
                    .then((response) => {
                        // handle success
                        console.log('then');
                        vm.dataToast('ok');
                        vm.loadStrategies()
                    })
                    .catch((error) => {
                        // handle error
                        vm.dataToast('ko');
                    });
            },

            deleteStrategy(strategy) {
                let vm = this
                axios.delete('/strategies/'+strategy.id)
                    .then((response) => {
                        // handle success
                        vm.dataToast('ok');
                        vm.loadStrategies()
                    })
                    .catch((error) => {
                        // handle error
                        vm.dataToast('ko');
                    });
            },

            saveNewStrategy() {
                let vm = this
                let data = {
                    'name': this.newNameStrategy,
                    'description': this.newdDescriptionStategy,
                    'user_id': this.user.id
                }
                axios.post('/strategies', data)
                    .then((response) => {
                        // handle success
                        vm.loadStrategies()
                        vm.newNameStrategy = ''
                        vm.dataToast('ok');
                    })
                    .catch((error) => {
                        // handle error
                        vm.dataToast('ko');
                    });
            },

            editUser(userId) {
                let vm = this
                let data = {
                    'name': this.user.name,
                    'email': this.user.email
                }
                axios.put('/users/'+userId, data)
                    .then((response) => {
                        // handle success
                        console.log('then');
                        vm.dataToast('ok');
                    })
                    .catch((error) => {
                        // handle error
                        vm.dataToast('ko');
                    });
            },


            dataToast(val) {
                if(val=='ok') {
                    this.toastIcon = 'mood'
                    this.toastHeader = 'Changes saved!'
                    this.toastMessage = 'Your data has been successfully updated.'
                } else if (val == 'ko') {
                    this.toastIcon = 'mood_bad'
                    this.toastHeader = 'Changes failed!'
                    this.toastMessage = 'The changes could not be updated.'
                }
                this.methodShowToast();
            },

            methodShowToast() {
                this.showToast = true;
                setTimeout(function() {
                    $('.toast').toast('show')
                }, 500);
            }
        },
        mounted() {
            this.loadStrategies()
            this.loadUser()
        }
    }
</script>
