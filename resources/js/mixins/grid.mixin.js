import { mapGetters, mapActions } from 'vuex';

export default {
  mounted() {
    this.buildGrid();
  },
  data() {
    return {
      containerWidth: undefined,
      containerHeight: undefined,
      gridSize: undefined,
      selectedCellPosition: [0, 0],
    };
  },
  computed: {
    ...mapGetters({
      justUpdated: 'isJustUpdated',
      currentCategoryType: 'getCurrentCategoryType'
    }),
    cellWidth() {
      if (this.gridSize) {
        return this.containerWidth / this.gridSize[0];
      }
    },
    cellHeight() {
      if (this.gridSize) {
        return this.containerHeight / this.gridSize[1];
      }
    },
    overCardPosition() {
      return [
        this.shouldDisplayPanel ? 0 : this.selectedCellPosition[0] + 3.5,
        this.shouldDisplayPanel ? 0 : this.selectedCellPosition[1] + 3.5
      ];
    },
    overCardDimension() {
      return [
        this.shouldDisplayPanel ? '100%' : this.cellWidth - 14 + 'px',
        this.shouldDisplayPanel ? '100%' : this.cellHeight - 14 + 'px',
      ];
    }
  },
  methods: {
    ...mapActions({
      setShowDisplayPanel: 'setShowDisplayPanel',
      setJustUpdated: 'setJustUpdated',
    }),
    buildGrid() {
      this.selectedCellPosition = [0, 0];
      let i = this.getItemsLength(this.items);
      this.gridSize = undefined;
      while (!this.gridSize) {
        this.gridSize = this.getGridDimensions(this.findFactors(i));
        i++;
      }
    },
    isPrime(num) {
      for (let i = 2, s = Math.sqrt(num); i <= s; i++)
        if (num % i === 0) return false;
      return num > 1;
    },
    getItemsLength(items) {
      if (this.isPrime(items.length) && items.length > 3) {
        return items.length + 1;
      }
      return items.length;
    },
    findFactors(n) {
      if (n === 1) {
        return [[1, 1]];
      }
      const combinations = [];

      for (let i = n; i > 0; i--) {
        let divisionResult = n / 1;
        if (n % i === 0) {
          combinations.push([i, n / i]);
        }
      }
      return combinations;
    },
    getGridDimensions(combinations) {
      const widthHeightRatio = this.containerWidth / this.containerHeight;

      let selectedCombination;
      let closestRatio;

      combinations.forEach(combination => {
        let combinationRatio = combination[0] / combination[1];
        if (!selectedCombination) {
          selectedCombination = combination;
          closestRatio = combinationRatio;
        } else {
          if (
            Math.abs(widthHeightRatio - combinationRatio) <
            Math.abs(widthHeightRatio - closestRatio)
          ) {
            closestRatio = combinationRatio;
            selectedCombination = combination;
          }
        }
      });

      return selectedCombination;
    },
    getCellStatusColor(item) {

      const bound = this.getBoundsSum(item);
      const sum = this.getExpensesSum(item);
      const ratio = this.currentCategoryType === 'expense' ? bound / sum : sum / bound;

      if (isNaN(ratio)) {
        return "rgba(100, 100, 100, 1)";
      }

      let computedValue;

      if (ratio === 0) {
        computedValue = 0;
      }

      const baseValue = 150;
      computedValue = baseValue * ratio;

      if (computedValue > baseValue * 2) {
        computedValue = baseValue * 2;
      }

      let r = 255;
      // let g = 255 - (baseValue / 2);
      let g = 200 - baseValue;
      let b = 100;

      if (computedValue > baseValue) {
        g += baseValue;
        r -= computedValue - baseValue;

        return `rgba(${r},${g},${b},1)`;
      }

      g += computedValue;
      return `rgba(${r},${g},${b},1)`;
    },
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
        if (
          vnode.context.selectedItem === binding.value &&
          vnode.context.justUpdated !== binding.value
        ) {
          vnode.context.selectedCellPosition = [el.offsetTop, el.offsetLeft];
          vnode.context.setJustUpdated(binding.value);
          setTimeout(() => {
            vnode.context.setShowDisplayPanel(true);
          }, 100);
        }
      }
    }
  },
  watch: {
    items(newValue, oldValue) {
      if (newValue.length !== oldValue.length) {
        this.buildGrid();
      }
    }
  }
}
