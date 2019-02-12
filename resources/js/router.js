import Vue from 'vue';
import Router from 'vue-router';
Vue.use(Router);

import HomeComponent from './components/HomeComponent';
import CubesComponent from './components/CubesComponent';

export default new Router({
	routes: [
		{
			path: '',
			name: 'home',
			component: CubesComponent
		},
		{
			path: '/cubes',
			name: 'cubes',
			component: CubesComponent
		}
	]
});