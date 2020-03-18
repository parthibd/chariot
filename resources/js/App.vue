<template>
    <v-app>
        <component v-bind:is="layout"></component>
    </v-app>
</template>

<style>
    :root {
        --accent-color: #FFCB08;
        --primary-color: #820263;
        --dark-color: #2E294E;
    }

    body {
        font-family: 'Roboto', sans-serif;
    }
</style>

<script>
    import AppLayout from './layouts/AppLayout.vue'
    import SimpleLayout from './layouts/SimpleLayout.vue'

    export default {
        computed: {
            layout() {
                return this.$store.getters.layout
            }
        },
        components: {
            'app-layout': AppLayout,
            'simple-layout': SimpleLayout
        }
    }
</script>
