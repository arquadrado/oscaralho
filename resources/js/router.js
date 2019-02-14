import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

import HomeComponent from './components/HomeComponent';
import GridComponent from './components/GridComponent';

export default new Router({
	routes: [
		{
			path: '',
			name: 'home',
			component: GridComponent
		},
		{
			path: '/cubes',
			name: 'cubes',
			component: GridComponent
		}
	]
});