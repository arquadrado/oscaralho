<template>
    <div id="cubes" v-cubes>
        <div class="cube"
          v-for="category in categories" 
          :key="category.id" 
          :style="{'width': tileWidth + 'px', 'height': tileHeight + 'px'}" 
          :class="{'selected': selectedCard === category}"
          @click="selectCard(category)"
        >
          <div class="card" v-card="category"></div>
        </div>
        
        <div class="card-to-expand"
          :class="{'expand': shouldExpand}"
          :style="{'width': overCardDimension[0], 'height': overCardDimension[1], 'top': overCardPosition[0] + 'px', 'left': overCardPosition[1] + 'px'}"
          @click="closeOverCard"
        ></div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
export default {
  data() {
    return {
      windowWidth: undefined,
      windowHeight: undefined,
      gridSize: undefined,
      selectedCard: undefined,
      selectCardPosition: [0, 0],
      shouldExpand: false,
      justUpdated: undefined
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
    },
    overCardPosition() {
      return [
        this.shouldExpand ? 0 : this.selectCardPosition[0],
        this.shouldExpand ? 0 : this.selectCardPosition[1],
      ];
    },
    overCardDimension() {
      return [
        this.shouldExpand ? '100%' : this.tileWidth + 'px',
        this.shouldExpand ? '100%' : this.tileHeight + 'px',
      ];
    }
  },
  methods: {
    ...mapActions({
      addCategory: 'addCategory'
    }),
    selectCard(card) {
      if (this.selectedCard === card) {
        this.selectedCard = undefined;
        this.justUpdated = undefined;
      } else {
        this.selectedCard = card;
      }
    },
    closeOverCard() {
      this.shouldExpand = false;
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
    },
    card: {
      update(el, binding, vnode) {
        if (vnode.context.selectedCard === binding.value && vnode.context.justUpdated !== binding.value) {
          console.log(el.offsetTop, el.offsetLeft);
          vnode.context.selectCardPosition = [el.offsetTop, el.offsetLeft];
          vnode.context.justUpdated = binding.value;
          setTimeout(() => {
            vnode.context.shouldExpand = true;
          }, 100);
        }
      }
    }
  }
};
</script>
