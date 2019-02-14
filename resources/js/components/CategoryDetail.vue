<template>
    <div class="category-detail">
        <h1>{{ selectedCategory.name }}</h1>
        <span @click="setShowDisplayPanel(false)">close</span>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";

    export default {
        computed: {
            ...mapGetters({
              selectedCategory: 'getSelectedCategory'
            }),
        },
        methods: {
            ...mapActions({
              setShowDisplayPanel: 'setShowDisplayPanel'
            })
        }
    }
</script>