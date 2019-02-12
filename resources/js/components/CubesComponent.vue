<template>
    <div id="cubes" v-cubes>
        <div class="cube" v-for="category in categories" :key="category.id" :style="{'width': tileWidth + 'px', 'height': tileHeight + 'px'}" @click="add({name: 'ai caralho'})"></div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  data() {
    return {
      windowWidth: undefined,
      windowHeight: undefined,
      gridSize: undefined
    };
  },
  mounted() {
    this.buildGrid();
  },
  computed: {
    ...mapGetters({
      categories: 'getCategories'
    }),
    tileWidth() {
      if (this.gridSize) {
        return (this.windowWidth) / this.gridSize[0]
      }
    },
    tileHeight() {
      if (this.gridSize) {
        return (this.windowHeight) / this.gridSize[1]
      }
    }
  },
  methods: {
    ...mapActions({
      addCategory: 'addCategory'
    }),
    add(category) {
      this.addCategory(category)
      this.buildGrid();
    },
    buildGrid() {
      let i = this.categories.length;
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

      const filteredCombinations = combinations.reduce((reduced, combination, index, array) => {
        const combinationExist = reduced.some((c) => {
            return c[0] === combination[0] && c[1] === combination[1] ||
              c[0] === combination[1] && c[1] === combination[0]
        })

        if (!combinationExist) {
          reduced.push(combination);
        }

        return reduced;
      }, []);

      filteredCombinations.splice(0, 1);

      return filteredCombinations;

    },
    getGridDimensions(combinations) {
      const widthHeightRatio = this.windowWidth / this.windowHeight;

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

      combinations.forEach((combination) => {
        combination.reverse();
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
    cubes: {
      inserted(el, binding, vnode) {
        console.log('inserted', el);
        vnode.context.windowWidth = el.offsetWidth;
        vnode.context.windowHeight = el.offsetHeight;
      }
    }
  }
};
</script>
