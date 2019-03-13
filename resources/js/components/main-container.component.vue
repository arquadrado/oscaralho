<template>
  <div class="container">
    <div class="header centered-content-hv">
      <div class="title">
        <span>{{ currentCategoryTypeLabel }}</span><br>
        <span class="subtitle">{{ currentViewLabel }}</span>
      </div>
    </div>
    <component :is="currentView"></component>
    <app-menu></app-menu>
    <app-modal></app-modal>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import GridMonthComponent from './grid-month.component.vue';
import GridYearComponent from './grid-year.component.vue';
import GridAllTimeComponent from './grid-all-time.component.vue';
import MenuComponent from './menu.component.vue';
import ModalComponent from './modal.component.vue';

export default {
  components: {
    'grid-month': GridMonthComponent,
    'grid-year': GridYearComponent,
    'grid-all-time': GridAllTimeComponent,
    'app-menu': MenuComponent,
    'app-modal': ModalComponent
  },
  data() {
    return {};
  },
  computed: {
    ...mapGetters({
      currentView: 'getCurrentView',
      selectedYear: 'getSelectedYear',
      selectedMonth: 'getSelectedMonth',
      getCurrentCategoryType: 'getCurrentCategoryType',
      shouldDisplayModal: 'shouldDisplayModal'
    }),
    currentViewLabel() {
      switch (this.currentView) {
        case 'grid-month':
          return `${this.selectedYear} - ${this.selectedMonth}`;
        case 'grid-year':
          return this.selectedYear;
        case 'grid-all-time':
          return 'All time';
      }
    },
    currentCategoryTypeLabel() {
      return this.getCurrentCategoryType === 'expense'
        ? 'Expenses'
        : 'Revenues';
    }
  },
  methods: {}
};
</script>
