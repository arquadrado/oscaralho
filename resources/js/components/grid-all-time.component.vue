<template>
  <div id="grid" v-grid>
    <div class="grid-cell"
      v-card="year"
      v-for="year in items"
      :key="year"
      :style="{'width': cellWidth + 'px', 'height': cellHeight + 'px'}"
      @click="clickYear(year)"
    >
      <div 
        class="category"
        :style="{'background-color': getCellStatusColor(year)}"
      >
        <i v-if="categoryViewMode === 'icon'">{{ year }}</i>
        <span v-if="categoryViewMode === 'ratio'">{{ getYearRatio(year) }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import CategoryDetail from './category-detail.component.vue';
import GridMixin from '../mixins/grid.mixin';
export default {
  mixins: [GridMixin],
  data() {
    return {};
  },
  computed: {
    ...mapGetters({
      bounds: 'getBounds',
      items: 'getAllTimeYears'
    })
  },
  methods: {
    ...mapActions({
      setCurrentView: 'setCurrentView',
      setYear: 'setYear'
    }),
    clickYear(year) {
      this.setCurrentView('grid-year');
      this.setYear(year);
    },
    getYearRatio(year) {
      const expenseSum = this.getExpensesSum(year);
      const yearBound = this.getBoundsSum(year);
      return `${yearBound}/${expenseSum.toFixed(2)}`;
    },
    getExpensesSum(year) {
      return this.bounds
        .filter(bound => {
          if (this.currentCategoryType === 'expense') {
            return bound.year === year && bound.category.expense;
          }
          return bound.year === year && !bound.category.expense;
        })
        .reduce((sum, bound) => {
          sum += bound.expenses.reduce((expenseSum, expense) => {
            expenseSum += Number(expense.value);
            return expenseSum;
          }, 0);
          return sum;
        }, 0);
    },
    getBoundsSum(year) {
      return this.bounds
        .filter(bound => {
          if (this.currentCategoryType === 'expense') {
            return bound.year === year && bound.category.expense;
          }
          return bound.year === year && !bound.category.expense;
        })
        .reduce((sum, bound) => {
          sum += bound.bound_in_cents / 100;
          return sum;
        }, 0);
    }
  }
};
</script>
