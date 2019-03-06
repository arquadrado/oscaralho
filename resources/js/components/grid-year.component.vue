<template>
  <div id="grid" v-grid>
    <div class="grid-cell"
      v-card="month"
      v-for="month in items"
      :key="month"
      :style="{'width': cellWidth + 'px', 'height': cellHeight + 'px'}"
      @click="clickMonth(month)"
    >
      <div 
        class="category"
        :style="{'background-color': getCellStatusColor(month)}"
      >
        <i>{{ month }}</i>
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
  components: {
    'category-detail': CategoryDetail
  },
  data() {
    return {};
  },
  computed: {
    ...mapGetters({
      bounds: 'getCategoriesByYear',
      items: 'getCurrentYearMonths',
      selectedYear: 'getSelectedYear'
    })
  },
  methods: {
    ...mapActions({
      setCurrentView: 'setCurrentView',
      setMonth: 'setMonth'
    }),
    clickMonth(month) {
      this.setCurrentView('grid-month');
      this.setMonth(month);
    },
    getExpensesSum(month) {
      return this.bounds
        .filter(bound => {
          if (this.currentCategoryType === 'expense') {
            return (
              bound.year === this.selectedYear &&
              bound.month === month &&
              bound.category.expense
            );
          }
          return (
            bound.year === this.selectedYear &&
            bound.month === month &&
            !bound.category.expense
          );
        })
        .reduce((sum, bound) => {
          sum += bound.expenses.reduce((expenseSum, expense) => {
            expenseSum += Number(expense.value);
            return expenseSum;
          }, 0);
          return sum;
        }, 0);
    },
    getBoundsSum(month) {
      return this.bounds
        .filter(bound => {
          if (this.currentCategoryType === 'expense') {
            return (
              bound.year === this.selectedYear &&
              bound.month === month &&
              bound.category.expense
            );
          }
          return (
            bound.year === this.selectedYear &&
            bound.month === month &&
            !bound.category.expense
          );
        })
        .reduce((sum, bound) => {
          sum += bound.bound_in_cents / 100;
          return sum;
        }, 0);
    }
  }
};
</script>
