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
        :style="{'background-color': getYearStatusColor(year)}"
      >
        <i>{{ year }}</i>
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
      bounds: 'getCategories',
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
    getYearStatusColor(year) {
      const bound = this.getYearBoundsSum(year);
      const sum = this.getYearExpensesSum(year);
      return this.getCellStatusColor(bound, sum);
    },
    getYearExpensesSum(year) {
      return this.bounds
        .filter(bound => {
          return bound.year === year;
        })
        .reduce((sum, bound) => {
          sum += bound.expenses.reduce((expenseSum, expense) => {
            expenseSum += Number(expense.value);
            return expenseSum;
          }, 0);
          return sum;
        }, 0);
    },
    getYearBoundsSum(year) {
      return this.bounds
        .filter(bound => {
          return bound.year === year;
        })
        .reduce((sum, bound) => {
          sum += bound.bound_in_cents / 100;
          return sum;
        }, 0);
    }
  }
};
</script>
