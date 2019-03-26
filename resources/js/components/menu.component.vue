<template>
  <div id="menu" :class="{'open': menuIsOpen}">
    <div class="opened-menu-content" v-if="menuIsOpen">

        <div class="slidable-panel"
          :class="{
            'move-left': isMenuBudgets || isMenuEditBudget,
            'move-right': isMenuCategories || isMenuEditCategory,
            'move-down': isMenuEditCategory || isMenuEditBudget || isStats
            }"
        >

          <div class="menu-options x0">

            <div class="menu-option" @click="changeMenuView('category')">
              <span>Categories</span>
            </div>

            <div class="menu-option" @click="changeMenuView('budget')">
              <span>Budgets</span>
            </div>

            <div class="menu-option" @click="changeMenuView('stats')">
              <span>Stats</span>
            </div>
<!-- 
            <a :href="exportCurrentViewPath" target="_blank">
              <div class="menu-option">
                <span>Export current</span>
              </div>
            </a> -->

            <a :href="exportAllPath" target="_blank">
              <div class="menu-option">
                <span>Export all</span>
              </div>
            </a>

          </div>

          <div class="menu-options x0 y1">
            <menu-stats></menu-stats>
            <div class="menu-option" @click="changeMenuView('options')">
              <span>Back</span>
            </div>
          </div>

          <div class="menu-options x1">
            <div class="menu-option" @click="changeMenuView('options')">
              <span>Back</span>
            </div>
            <div class="menu-option" @click="editCategory()">
              <span>New category</span>
            </div>
            <div class="menu-option" v-for="category in categoriesToEdit" :key="category.id" @click="editCategory(category)">
              <span v-if="category.expense" class="icon"><i class="fa fa-minus"> </i></span>
              <span v-else class="icon"><i class="fa fa-money"> </i></span>
              <span>{{ category.name }}</span>
            </div>
          </div>

          <div class="menu-options x1 y1">
            <menu-add-category v-if="isMenuEditCategory" @done="changeMenuView('category')"></menu-add-category>
            <div class="menu-option" @click="changeMenuView('category')">
              <span>Back</span>
            </div>
          </div>

          <div class="menu-options x-1">
            <div class="menu-option" @click="changeMenuView('options')">
              <span>Back</span>
            </div>
            <div class="menu-option" @click="editBudget()">
              <span>New budget</span>
            </div>
            <div class="menu-option" v-for="budget in budgetsToEdit" :key="budget.id" @click="editBudget(budget)">
              <span>{{ budget.year }} - {{ budget.month }}</span>
            </div>
          </div>

          <div class="menu-options x-1 y1">
            <menu-add-budget v-if="isMenuEditBudget" @done="changeMenuView('budget')"></menu-add-budget>
            <div class="menu-option" @click="changeMenuView('budget')">
              <span>Back</span>
            </div>
          </div>

        </div>
    </div>

    <div class="menu-trigger">
      <span @click="toggleMenu" class="arrow-button centered-content-hv"><i class="fa fa-bars"></i></span>

      <span class="arrow-button centered-content-hv" :class="{'disabled': menuIsOpen}" @click="toggleCategoryType">
        <i class="fa" :class="{
        'fa-circle': isExpense,
        'fa-circle-thin': !isExpense,
        }"></i>
      </span>

      <span class="arrow-button centered-content-hv" :class="{'disabled': menuIsOpen}" @click="toggleCategoryViewMode"><i class="fa fa-eye"></i></span>

      <span class="arrow-button centered-content-hv" :class="{'disabled': !canGoUp || menuIsOpen}" @click="up"><i class="fa fa-angle-up"></i></span>
      <span class="arrow-button centered-content-hv" :class="{'disabled': !canGoDown || menuIsOpen}" @click="down"><i class="fa fa-angle-down"></i></span>
      <span class="arrow-button centered-content-hv" :class="{'disabled': !canGoBack || menuIsOpen}" @click="back"><i class="fa fa-angle-left"></i></span>
      <span class="arrow-button centered-content-hv" :class="{'disabled': !canGoForward || menuIsOpen}" @click="forward"><i class="fa fa-angle-right"></i></span>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import MenuAddCategoryComponent from './menu-add-category.component.vue';
import MenuAddBudgetComponent from './menu-add-budget.component.vue';
import MenuStats from './menu-stats.component.vue';

export default {
  components: {
    'menu-add-category': MenuAddCategoryComponent,
    'menu-add-budget': MenuAddBudgetComponent,
    'menu-stats': MenuStats
  },
  data() {
    return {
      menuIsOpen: false,
      menuDisplay: 'options'
    };
  },
  computed: {
    ...mapGetters({
      currentView: 'getCurrentView',
      selectedYear: 'getSelectedYear',
      selectedMonth: 'getSelectedMonth',
      currentYearMonths: 'getCurrentYearMonths',
      allYears: 'getAllTimeYears',
      categoryViewMode: 'getCategoryViewMode',
      getCurrentCategoryType: 'getCurrentCategoryType',

      categoriesToEdit: 'getCategories',
      budgetsToEdit: 'getBudgetsToEdit'
    }),
    menuTriggerLabel() {
      switch (this.currentView) {
        case 'grid-month':
          return `${this.selectedYear} - ${this.selectedMonth}`;
        case 'grid-year':
          return this.selectedYear;
        case 'grid-all-time':
          return 'All time';
      }
    },
    shouldShowYearViewButton() {
      return this.currentView === 'grid-month';
    },
    canGoUp() {
      return !this.menuIsOpen && (
        (this.currentView === 'grid-month' && this.selectedMonth) ||
        (this.currentView === 'grid-year' && this.selectedYear)
      );
    },
    canGoDown() {
      return !this.menuIsOpen && (
        (this.currentView === 'grid-all-time' && this.selectedYear) ||
        (this.currentView === 'grid-year' && this.selectedMonth)
      );
    },
    canGoBack() {
      return !this.menuIsOpen && (
        (this.currentYearMonths.indexOf(this.selectedMonth) > 0 &&
          this.currentView === 'grid-month') ||
        this.allYears.indexOf(this.selectedYear) > 0
      );
    },
    canGoForward() {
      return !this.menuIsOpen && (
        (this.currentYearMonths.indexOf(this.selectedMonth) <
          this.currentYearMonths.length - 1 &&
          this.currentView === 'grid-month') ||
        this.allYears.indexOf(this.selectedYear) < this.allYears.length - 1
      );
    },
    isExpense() {
      return this.getCurrentCategoryType === 'expense';
    },
    isMenuOptions() {
      return this.menuDisplay === 'options';
    },
    isMenuCategories() {
      return this.menuDisplay === 'category';
    },
    isMenuEditCategory() {
      return this.menuDisplay === 'category-edit';
    },
    isMenuAddMonth() {
      return this.menuDisplay === 'month';
    },
    isMenuBudgets() {
      return this.menuDisplay === 'budget';
    },
    isMenuEditBudget() {
      return this.menuDisplay === 'budget-edit';
    },
    isStats() {
      return this.menuDisplay === 'stats';
    },
    exportCurrentViewPath() {
      return `/export/${this.selectedYear}/${this.selectedMonth}`;
    },
    exportAllPath() {
      return '/export';
    },
  },
  methods: {
    ...mapActions({
      setYear: 'setYear',
      setMonth: 'setMonth',
      setCurrentView: 'setCurrentView',
      setCurrentCategoryType: 'setCurrentCategoryType',
      setCategoryToEdit: 'setCategoryToEdit',
      setBudgetToEdit: 'setBudgetToEdit',
      setCategoryViewMode: 'setCategoryViewMode'
    }),
    toggleMenu() {
      this.changeMenuView('options');
      this.menuIsOpen = !this.menuIsOpen;
    },
    toggleCategoryViewMode() {
      if (!this.menuIsOpen) {
        switch (this.categoryViewMode) {
          case 'icon':
            this.setCategoryViewMode('ratio');
            break;
          case 'ratio':
            this.setCategoryViewMode('icon');
            break;
        }
      }
    },
    up() {
      if (this.canGoUp) {
        if (this.currentView === 'grid-month') {
          this.setCurrentView('grid-year');
        } else {
          this.setCurrentView('grid-all-time');
        }
      }
    },
    down() {
      if (this.canGoDown) {
        if (this.currentView === 'grid-all-time') {
          this.setCurrentView('grid-year');
        } else {
          this.setCurrentView('grid-month');
        }
      }
    },
    back() {
      if (this.canGoBack) {
        const monthIndex = this.currentYearMonths.indexOf(this.selectedMonth);

        if (monthIndex > 0 && this.currentView === 'grid-month') {
          this.setMonth(this.currentYearMonths[monthIndex - 1]);
        } else {
          const yearIndex = this.allYears.indexOf(this.selectedYear);
          this.setYear(this.allYears[yearIndex - 1]);
          this.setMonth(
            this.currentYearMonths[this.currentYearMonths.length - 1]
          );
        }
      }
    },
    forward() {
      if (this.canGoForward) {
        const monthIndex = this.currentYearMonths.indexOf(this.selectedMonth);

        if (
          monthIndex < this.currentYearMonths.length - 1 &&
          this.currentView === 'grid-month'
        ) {
          this.setMonth(this.currentYearMonths[monthIndex + 1]);
        } else {
          const yearIndex = this.allYears.indexOf(this.selectedYear);
          this.setYear(this.allYears[yearIndex + 1]);
          this.setMonth(this.currentYearMonths[0]);
        }
      }
    },
    toggleCategoryType() {
      if (!this.menuIsOpen) {
        const current = this.getCurrentCategoryType;
        this.setCurrentCategoryType(
          current === 'expense' ? 'revenue' : 'expense'
        );
      }
    },
    changeMenuView(view) {
      this.menuDisplay = view;
    },
    editCategory(category) {
      this.setCategoryToEdit(category);
      this.changeMenuView('category-edit');
    },
    editBudget(budget) {
      this.setBudgetToEdit(budget);
      this.changeMenuView('budget-edit');
    },
    
  }
};
</script>
