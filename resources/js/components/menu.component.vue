<template>
  <div id="menu" :class="{'open': menuIsOpen}">
    <div class="opened-menu-content" v-if="menuIsOpen">

        <div class="slidable-panel"
          :class="{
            'move-left': isMenuBudgets || isMenuEditBudget,
            'move-right': isMenuCategories || isMenuEditCategory,
            'move-down': isMenuEditCategory || isMenuEditBudget
            }"
        >

          <div class="menu-options x0">

            <div class="menu-option" @click="changeMenuView('category')">
              <span>Categories</span>
            </div>

            <div class="menu-option" @click="changeMenuView('budget')">
              <span>Budgets</span>
            </div>

            <div v-if="shouldShowYearViewButton" class="menu-option" @click="showYearView">
              <span>Year</span>
            </div>

            <div class="menu-option" @click="showAllTimeView">
              <span>All time</span>
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
      <span class="arrow-button centered-content-hv" :class="{'disabled': isExpense}" @click="setCurrentCategoryType('expense')"><i class="fa fa-circle-thin"></i></span>
      <span class="arrow-button centered-content-hv" :class="{'disabled': !isExpense}" @click="setCurrentCategoryType('revenue')"><i class="fa fa-circle-thin"></i></span>
      <span class="arrow-button centered-content-hv" :class="{'disabled': !canGoBack}" @click="back"><i class="fa fa-angle-left"></i></span>
      <span class="arrow-button centered-content-hv" :class="{'disabled': !canGoForward}" @click="forward"><i class="fa fa-angle-right"></i></span>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import MenuAddCategoryComponent from './menu-add-category.component.vue';
import MenuAddBudgetComponent from './menu-add-budget.component.vue';

export default {
  components: {
    'menu-add-category': MenuAddCategoryComponent,
    'menu-add-budget': MenuAddBudgetComponent
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
      getCurrentCategoryType: 'getCurrentCategoryType',

      categoriesToEdit: 'getCategoriesToEdit',
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
    canGoBack() {
      return (
        (this.currentYearMonths.indexOf(this.selectedMonth) > 0 &&
          this.currentView === 'grid-month') ||
        this.allYears.indexOf(this.selectedYear) > 0
      );
    },
    canGoForward() {
      return (
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
    }
  },
  methods: {
    ...mapActions({
      setYear: 'setYear',
      setMonth: 'setMonth',
      setCurrentView: 'setCurrentView',
      setCurrentCategoryType: 'setCurrentCategoryType',
      setCategoryToEdit: 'setCategoryToEdit',
      setBudgetToEdit: 'setBudgetToEdit'
    }),
    toggleMenu() {
      this.changeMenuView('options');
      this.menuIsOpen = !this.menuIsOpen;
    },
    showYearView() {
      this.setCurrentView('grid-year');
      this.setMonth();
      this.toggleMenu();
    },
    showAllTimeView() {
      this.setCurrentView('grid-all-time');
      this.setMonth();
      this.setYear();
      this.toggleMenu();
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
    changeMenuView(view) {
      console.log(view);
      this.menuDisplay = view;
    },
    editCategory(category) {
      this.setCategoryToEdit(category);
      this.changeMenuView('category-edit');
    },
    editBudget(budget) {
      this.setBudgetToEdit(budget);
      this.changeMenuView('budget-edit');
    }
  }
};
</script>
