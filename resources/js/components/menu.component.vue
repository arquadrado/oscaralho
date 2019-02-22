<template>
  <div id="menu" :class="{'open': menuIsOpen}">
    <div class="menu-trigger" @click="toggleMenu">
      <span>2019-02</span>
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {
      menuIsOpen: false,
    }
  },
  methods: {
    toggleMenu() {
      this.menuIsOpen = !this.menuIsOpen;
    }
  }
}
</script>
