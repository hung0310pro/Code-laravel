<template>
    <div class="child childA">
        <h1> Score: {{ score }} </h1>
        <button @click="changeScore()">Change Score</button>
    </div>
</template>

<script>
    export default {
        computed: {
            score () {
                return this.$store.getters['scoreBoard/score'];
            }
        },
        methods: {
            changeScore () {
                this.$store.dispatch('scoreBoard/incrementScore', 0);
            }
        }
    };
</script>

<style scoped>
    .child {
        width: 150px;
        height: 150px;
        position:absolute;
        top: 60px;
        padding: 0px 5px 5px;
    }
    .childA {
        background-color: red;
        left: 20px;
    }
</style>