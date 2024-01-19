<template>
    <div :id="colore + 'alerts' + messaggio" class="alert alert-dismissible fade show alert-style" :class="'alert-' + colore" role="alert">
        {{ messaggio }}
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</template>

<script>
    export default {
        props: {
            'colore': {
                required: false,
            },
            'messaggio': {
                required: false,
            },
        },
        mounted() {
            setTimeout(this.nascondiAlert, 5000);
        },
        methods: {
            nascondiAlert() {
                document.getElementById(this.colore + 'alerts' + this.messaggio).style.display = 'none';
            }
        },
    }
</script>

<style scoped>
.alert-style {
    margin: 0;
    position: fixed;
    top: 66px;
    left: 0;
    width: 100%;
    z-index: 9999999999999999999999;
}
</style>
