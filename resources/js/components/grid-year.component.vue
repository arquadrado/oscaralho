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
            :style="{'background-color': getMonthStatusColor(month)}"
          >
            <i>{{ month }}</i>
          </div>
        </div>

        <!-- <div class="category-to-expand"
          :class="{'expand': shouldDisplayPanel}"
          :style="{
            'background-color': getCellStatusColor(selectedCategoryObject),
            'width': overCardDimension[0], 
            'height': overCardDimension[1],
            'top': overCardPosition[0] + 'px', 
            'left': overCardPosition[1] + 'px'
          }"
        > -->
          <!-- <component :is="'category-detail'" v-if="shouldDisplayPanel"></component> -->
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import CategoryDetail from './CategoryDetail.vue';
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
      selectedItem: 'getSelectedCategoryId',
      selectedCategoryObject: 'getSelectedCategory',
      shouldDisplayPanel: 'shouldDisplayPanel',
      selectedYear: 'getSelectedYear',
      selectedMonth: 'getSelectedMonth'
    })
  },
  methods: {
    ...mapActions({
      selectCategory: 'selectCategory',
      setJustUpdated: 'setJustUpdated',
      setCurrentView: 'setCurrentView',
      setMonth: 'setMonth'
    }),
    clickMonth(month) {
      // if (this.justUpdated === categoryId) {
      //   this.setJustUpdated();
      // }
      // this.selectCategory(categoryId);
      this.setCurrentView('grid-month');
      this.setMonth(month);
    },
    getMonthStatusColor(month) {
      const bound = this.getMonthBoundsSum(month);
      const sum = this.getMonthExpensesSum(month);
      return this.getCellStatusColor(bound, sum);
    },
    getMonthExpensesSum(month) {
      return this.bounds
        .filter(bound => {
          return bound.year === this.selectedYear && bound.month === month;
        })
        .reduce((sum, bound) => {
          sum += bound.expenses.reduce((expenseSum, expense) => {
            expenseSum += Number(expense.value);
            return expenseSum;
          }, 0);
          return sum;
        }, 0);
    },
    getMonthBoundsSum(month) {
      return this.bounds
        .filter(bound => {
          return bound.year === this.selectedYear && bound.month === month;
        })
        .reduce((sum, bound) => {
          sum += bound.bound_in_cents / 100;
          return sum;
        }, 0);
    }
  }
};
</script>
