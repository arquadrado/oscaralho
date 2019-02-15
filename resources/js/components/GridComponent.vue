<template>
  <div class="container">
    <div id="grid" v-grid>
        <div class="grid-cell"
          v-for="category in categories"
          :key="category.id"
          :style="{'width': tileWidth + 'px', 'height': tileHeight + 'px'}"
          :class="{'selected': selectedCategory === category.id}"
          @click="clickCategory(category.id)"
        >
          <div class="category" v-card="category.id"></div>
        </div>

        <div class="grid-cell menu-trigger" :style="{'width': tileWidth + 'px', 'height': tileHeight + 'px'}" >
          <div class="category"></div>
        </div>

        <div class="category-to-expand"
          :class="{'expand': shouldDisplayPanel}"
          :style="{'width': overCardDimension[0], 'height': overCardDimension[1], 'top': overCardPosition[0] + 'px', 'left': overCardPosition[1] + 'px'}"
        >
          <!-- <category-detail v-if="shouldDisplayPanel"></category-detail> -->
          <component :is="'category-detail'" v-if="shouldDisplayPanel"></component>
        </div>
    </div>
    <!-- <div id="menu">
      <div class="menu-item"></div>
      <div class="menu-item"></div>
      <div class="menu-item"></div>
      <div class="menu-item"></div>
    </div> -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import CategoryDetail from './CategoryDetail.vue';
export default {
  components: {
    'category-detail': CategoryDetail
  },
  data() {
    return {
      containerWidth: undefined,
      containerHeight: undefined,
      gridSize: undefined,
      selectedCategoryPosition: [0, 0],
      justUpdated: undefined
    };
  },
  mounted() {
    this.buildGrid();
  },
  computed: {
    ...mapGetters({
      categories: 'getCategories',
      selectedCategory: 'getSelectedCategoryId',
      shouldDisplayPanel: 'shouldDisplayPanel'
    }),
    tileWidth() {
      if (this.gridSize) {
        return (this.containerWidth) / this.gridSize[0]
      }
    },
    tileHeight() {
      if (this.gridSize) {
        return (this.containerHeight) / this.gridSize[1]
      }
    },
    overCardPosition() {
      return [
        this.shouldDisplayPanel ? 0 : this.selectedCategoryPosition[0],
        this.shouldDisplayPanel ? 0 : this.selectedCategoryPosition[1],
      ];
    },
    overCardDimension() {
      return [
        this.shouldDisplayPanel ? '100%' : this.tileWidth + 'px',
        this.shouldDisplayPanel ? '100%' : this.tileHeight + 'px',
      ];
    }
  },
  methods: {
    ...mapActions({
      selectCategory: 'selectCategory',
      setShowDisplayPanel: 'setShowDisplayPanel'
    }),
    clickCategory(categoryId) {
      if (this.selectedCategory === categoryId) {
        this.justUpdated = undefined;
      }
      this.selectCategory(categoryId);
    },
    buildGrid() {
      let i = this.categories.length + 1;
      this.gridSize = undefined;
      while(!this.gridSize) {
        this.gridSize = this.getGridDimensions(this.findFactors(i));
        i++;
      }
    },
    findFactors(n) {
      const combinations = [];

      for (let i = n; i > 0; i--) {
        let divisionResult = n / 1;
        if (n % i === 0) {
          combinations.push([i , n / i]);
        }
      }

      return combinations.slice(1, combinations.length - 1);
    },
    getGridDimensions(combinations) {
      const widthHeightRatio = this.containerWidth / this.containerHeight;

      let selectedCombination;
      let closestRatio;

      combinations.forEach((combination) => {
        let combinationRatio = combination[0]/combination[1];
        if (!selectedCombination) {
          selectedCombination = combination;
          closestRatio = combinationRatio;
        } else {
          if (Math.abs(widthHeightRatio - combinationRatio) < Math.abs(widthHeightRatio - closestRatio)) {
            closestRatio = combinationRatio
            selectedCombination = combination;
          }
        }
      });

      return selectedCombination;
    }
  },
  directives: {
    grid: {
      inserted(el, binding, vnode) {
        vnode.context.containerWidth = el.offsetWidth;
        vnode.context.containerHeight = el.offsetHeight;
      }
    },
    card: {
      update(el, binding, vnode) {
        if (vnode.context.selectedCategory === binding.value && vnode.context.justUpdated !== binding.value) {
          vnode.context.selectedCategoryPosition = [el.offsetTop, el.offsetLeft];
          vnode.context.justUpdated = binding.value;
          setTimeout(() => {
            vnode.context.setShowDisplayPanel(true);
          }, 100);
        }
      }
    }
  }
};
</script>
