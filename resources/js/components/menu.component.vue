<template>
  <div id="menu" :class="{'open': menuIsOpen}">
    <div v-if="menuIsOpen" class="menu-options">

      <!-- <div class="menu-option" @click="showMonthView">
        <span>Month</span>
      </div> -->

      <div class="menu-option" @click="showYearView">
        <span>Year</span>
      </div>

      <div class="menu-option" @click="showAllTimeView">
        <span>All time</span>
      </div>

    </div>
    <div class="menu-trigger" @click="toggleMenu">
      <span>{{ menuTriggerLabel }}</span>
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
      selectedMonth: 'getSelectedMonth'
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
    }
  },
  methods: {
    ...mapActions({
      setYear: 'setYear',
      setMonth: 'setMonth',
      setCurrentView: 'setCurrentView'
    }),
    toggleMenu() {
      this.menuIsOpen = !this.menuIsOpen;
    },
    showYearView() {
      this.setCurrentView('grid-year');
      this.toggleMenu();
    },
    showAllTimeView() {
      // this.setCurrentView('grid-all-time');
    }
  }
};
</script>
