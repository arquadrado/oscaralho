<template>
  <div class="container">
    <component :is="currentView"></component>
    <app-menu></app-menu>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import GridMonthComponent from './grid-month.component.vue';
import GridYearComponent from './grid-year.component.vue';
import GridAllTimeComponent from './grid-all-time.component.vue';
import MenuComponent from './menu.component.vue';

export default {
  components: {
    'grid-month': GridMonthComponent,
    'grid-year': GridYearComponent,
    'grid-all-time': GridAllTimeComponent,
    'app-menu': MenuComponent
  },
  data() {
    return {};
  },
  computed: {
    ...mapGetters({
      currentView: 'getCurrentView'
    })
  },
  methods: {}
};
</script>
