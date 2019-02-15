<template>
    <div class="category-detail">
      <h1>{{ selectedCategory.name }}</h1>
      <div class="content">

        <div class="balance">
          <span>100/87.78</span>
        </div>
        <span class="unit">euros</span>

        <div class="add-button">
          <i class="fa fa-plus"></i>
        </div>

      </div>
      <span class="close-button" @click="setShowDisplayPanel(false)">close</span>
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
