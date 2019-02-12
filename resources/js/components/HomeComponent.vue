<template>
    <div id="home" :class="{'category-open': categoryDetail}">
        <div 
            class="category"
            v-for="category in categories"
            v-if="shouldDisplayCategory(category)"
            :key="category.name" 
            @click="selectCategory(category)"
            :class="{'selected': isCategorySelected(category), 'open': isCategoryDetailOpened(category)}"
        >
            <span class="name" @click="toggleCategoryDetail($event)">{{ category.name }}</span>
            <div class="input-expense" v-if="isCategorySelected(category) && !categoryDetailOpen">
                <input type="number" @click="$event.stopPropagation()" v-model="expenseValue">
            
                <div class="q-icon-button" @click="addExpense($event)">
                    <span class="fa fa-icon fa-plus add-expense"></span>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  data() {
    return {
      activeCategory: undefined,
      categoryDetailOpen: false,
      expenseValue: undefined,
      scrollPosition: undefined,
      shouldGetScrollPosition: false,
      shouldSetScrollPosition: false
    };
  },
  computed: {
    ...mapGetters({
      categories: "getCategories"
    }),
    categoryDetail() {
      return this.activeCategory && this.categoryDetailOpen;
    },
    homeContainerHeight() {
      return this.categoryDetailOpen
        ? "calc(100vh - 10px)"
        : `${this.categories.length * 150}px`;
    }
  },
  methods: {
    xis() {
      window.scrollBy(0, 100);
    },
    shouldDisplayCategory(category) {
      return !this.categoryDetailOpen || this.isCategoryDetailOpened(category);
    },
    isCategorySelected(category) {
      return (
        this.activeCategory !== undefined &&
        category.name === this.activeCategory
      );
    },
    isCategoryDetailOpened(category) {
      return this.isCategorySelected(category) && this.categoryDetailOpen;
    },
    selectCategory(category) {
      const canDeselectCategory =
        this.activeCategory === category.name && !this.categoryDetailOpen;

      if (canDeselectCategory) {
        this.activeCategory = undefined;
        return;
      }
      this.activeCategory = category.name;
    },
    toggleCategoryDetail(event) {
      if (this.categoryDetailOpen) {
        event.stopPropagation();
      } else {
        this.scrollPosition = window.scrollY;
        console.log(
          "should enter here when opening cat detail",
          this.scrollPosition
        );
      }
      this.categoryDetailOpen = !this.categoryDetailOpen;
    },
    addExpense(event) {
      event.stopPropagation();
      console.log("Expense added:", this.expenseValue);
      this.expenseValue = undefined;
    },
    setScrollPosition(position) {
      this.scrollPosition = position;
    }
  },
  directives: {
    keepScroll: {
      inserted(el, binding, vnode) {
        console.log("inserted");
      },
      update(el, binding, vnode) {
        // if (vnode.context.shouldGetScrollPosition) {
        //     vnode.context.setScrollPosition(el.scrollTop);
        // }
        // if (vnode.context.shouldSetScrollPosition) {
        //     vnode.context.setScrollPosition(el.scrollTop);
        //     el.scrollTop = vnode.context.scrollPosition;
        // }

        console.log(
          "update",
          el.scrollTop,
          el.offsetTop,
          el.scrollLeft,
          el.offsetLeft,
          el
        );
      }
    }
  },
  mounted() {
    console.log("Component mounted.");
    window.scrollBy(0, 500);
  },
  updated() {
    console.log("Component updated.", this.scrollPosition);
    window.scrollBy(0, 0);
    window.scrollBy(0, this.scrollPosition);
  }
};
</script>
