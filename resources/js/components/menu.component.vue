<template>
  <div id="menu" :class="{'open': menuIsOpen}">
    <div v-if="menuIsOpen" class="menu-options">

      <div v-if="shouldShowYearViewButton" class="menu-option" @click="showYearView">
        <span>Year</span>
      </div>

      <div class="menu-option" @click="showAllTimeView">
        <span>All time</span>
      </div>

    </div>
    <div class="menu-trigger">
      <span class="arrow-button centered-content-hv" :class="{'disabled': !canGoBack}" @click="back"><i class="fa fa-chevron-left"></i></span>
      <span class="arrow-button centered-content-hv" :class="{'disabled': isExpense}" @click="setCurrentCategoryType('expense')"><i class="fa fa-circle-o"></i></span>
      <span @click="toggleMenu" class="arrow-button centered-content-hv"><i class="fa fa-bars"></i></span>
      <span class="arrow-button centered-content-hv" :class="{'disabled': !isExpense}" @click="setCurrentCategoryType('revenue')"><i class="fa fa-circle-o"></i></span>
      <span class="arrow-button centered-content-hv" :class="{'disabled': !canGoForward}" @click="forward"><i class="fa fa-chevron-right"></i></span>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return {
      menuIsOpen: false
    };
  },
  computed: {
    ...mapGetters({
      currentView: 'getCurrentView',
      selectedYear: 'getSelectedYear',
      selectedMonth: 'getSelectedMonth',
      currentYearMonths: 'getCurrentYearMonths',
      allYears: 'getAllTimeYears',
      getCurrentCategoryType: 'getCurrentCategoryType'
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
    }
  },
  methods: {
    ...mapActions({
      setYear: 'setYear',
      setMonth: 'setMonth',
      setCurrentView: 'setCurrentView',
      setCurrentCategoryType: 'setCurrentCategoryType'
    }),
    toggleMenu() {
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
    }
  }
};
</script>
