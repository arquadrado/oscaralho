import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

import MainContainer from './components/main-container.component';
import DataVisualisation from './components/data-visualisation.component';

export default new Router({
  routes: [
    {
      path: '/visualisation',
      name: 'visualisation',
      component: DataVisualisation,
    },
    {
      path: '',
      name: 'home',
      component: MainContainer
    }
  ]
});
