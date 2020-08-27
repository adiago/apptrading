<style>
    .position-toast {
        position:fixed;
        top:auto;
        right:auto;
        left:auto;
        bottom:0;
        margin-bottom:5px;
    }
</style>
<template>
    <div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-end" style="min-height: 200px;">
        <div class="toast position-toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3500">
            <div class="toast-header text-white" :class="(icon == 'mood') ? 'bg-success' : 'bg-danger'">
                <i class="material-icons">{{icon}}</i>&nbsp;
                <strong class="mr-auto">{{header}}</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{message}}
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {

            }
        },
        props: ['icon', 'header', 'message'],
    }
</script>