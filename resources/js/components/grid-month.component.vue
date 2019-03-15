<template>
    <div id="grid" v-grid>
        <div class="grid-cell"
          v-card="item.id"
          v-for="item in items"
          :key="item.id"
          :style="{'width': cellWidth + 'px', 'height': cellHeight + 'px'}"
          :class="{'selected': selectedItem === item.id}"
          @click="clickBound(item.id)"
        >
          <div
            class="category"
            :style="{'background-color': getCellStatusColor(item)}"
          >
            <i :class="[getBoundCategory(item).icon]" v-if="categoryViewMode === 'icon'"></i>
            <span v-if="categoryViewMode === 'name'">{{ getBoundCategory(item).name }}</span>
            <span v-if="categoryViewMode === 'ratio'">{{ getBoundCategoryRatio(item) }}</span>
          </div>
        </div>

        <div class="message" v-if="items.length === 0">
          <p class="message" >
            You have no categories so this month's budget could not be automatically created.
          </p>
          <p>
            Use the (<i class="fa fa-bars"></i>) menu's section <strong>Categories</strong> to add new categories and refresh the page to create the current month's budget.
          </p>
          <p>
            You can also add budgets manually in the menu's section <strong>Budgets</strong>.
          </p>
        </div>

        <div class="category-to-expand"
          :class="{'expand': shouldDisplayPanel}"
          :style="{
            'background-color': getCellStatusColor(selectedCategoryObject),
            'width': overCardDimension[0],
            'height': overCardDimension[1],
            'top': overCardPosition[0] + 'px',
            'left': overCardPosition[1] + 'px'
          }"
        >
          <component :is="'category-detail'" :backgroundColor="getCellStatusColor(selectedCategoryObject)" v-if="shouldDisplayPanel"></component>
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
      categories: 'getCategories',
      items: 'getBoundsByMonth',
      selectedItem: 'getSelectedBoundId',
      selectedCategoryObject: 'getSelectedBound',
      shouldDisplayPanel: 'shouldDisplayPanel',
      selectedYear: 'getSelectedYear',
      selectedMonth: 'getSelectedMonth',
      categoryViewMode: 'getCategoryViewMode'
    })
  },
  methods: {
    ...mapActions({
      selectBound: 'selectBound',
      setJustUpdated: 'setJustUpdated'
    }),
    getBoundCategory(bound) {
      return this.categories.find(c => c.id === bound.category.id);
    },
    getBoundCategoryRatio(bound) {
      const expenseSum = this.getExpensesSum(bound);
      return `${bound.bound_in_cents / 100}/${expenseSum}`;
    },
    clickBound(boundId) {
      if (this.justUpdated === boundId) {
        this.setJustUpdated();
      }
      this.selectBound(boundId);
    },
    getExpensesSum(bound) {
      if (!bound || !bound.expenses) {
        return 0;
      }
      return bound.expenses.reduce((sum, expense) => {
        sum += Number(expense.value);
        return sum;
      }, 0);
    },
    getBoundsSum(bound) {
      if (!bound) {
        return 0;
      }
      return bound ? bound.bound_in_cents / 100 : 0;
    }
  }
};
</script>
