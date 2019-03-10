<template>
    <div id="grid" v-grid>
        <div class="grid-cell"
          v-card="category.id"
          v-for="category in items"
          :key="category.id"
          :style="{'width': cellWidth + 'px', 'height': cellHeight + 'px'}"
          :class="{'selected': selectedItem === category.id}"
          @click="clickCategory(category.id)"
        >
          <div 
            class="category"
            :style="{'background-color': getCellStatusColor(category)}"
          >
            <i :class="[category.category.icon]"></i>
          </div>
        </div>
        
        <div class="message" v-if="items.length === 0">
          <p class="message" >
            You have no categories so this month's budget could not be automatically created.
          </p>
          <p>
            Use the (<i class="fa fa-bars"></i>) menu's section <strong>Categories</strong> to add new categories and refresh the page to create the current's month budget.
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
          <component :is="'category-detail'" v-if="shouldDisplayPanel"></component>
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
      items: 'getBoundsByMonth',
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
      setJustUpdated: 'setJustUpdated'
    }),
    clickCategory(categoryId) {
      if (this.justUpdated === categoryId) {
        this.setJustUpdated();
      }
      this.selectCategory(categoryId);
    },
    getExpensesSum(category) {
      if (!category || !category.expenses) {
        return 0;
      }
      return category.expenses.reduce((sum, expense) => {
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
