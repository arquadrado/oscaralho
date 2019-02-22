<template>
  <div class="container">
    <grid></grid>
    <app-menu></app-menu>
  </div>
</template>

<script>
import GridComponent from './GridComponent.vue';
import MenuComponent from './menu.component.vue';

export default {
  components: {
    'grid': GridComponent,
    'app-menu': MenuComponent,
  },
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>
