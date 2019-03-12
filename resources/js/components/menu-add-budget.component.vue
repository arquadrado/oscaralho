<template>
  <div class="add-budget">

    <div class="form-field">
      <label for="budget-year">Year</label>
      <input type="number" name="budget-year" v-model="budgetForm.year">
    </div>

    <div class="form-field">
      <label for="budget-month">Month</label>
      <select name="budget-month" v-model="budgetForm.month">
        <option v-for="m in months" :key="m.number" :value="m.number">{{ m.name }}</option>
      </select>
    </div>


    <div class="actions">

      <span class="button" @click="save">
        <i class="fa fa-save"></i>
      </span>

      <span class="button" v-if="budgetToEdit" @click="remove">
        <i class="fa fa-trash-o"></i>
      </span>
    </div>

    <span><strong>Categories</strong></span>

    <div class="menu-option half"
      v-for="category in categories"
      :key="category.id"
      :class="{'selected': categoryIsToggledOn(category.id)}"
      @click="toggleCategory(category.id)"
    >
      <span>{{ category.name }}</span>
    </div>

  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  mounted() {
    if (this.budgetToEdit) {
      this.budgetForm.year = this.budgetToEdit.year;
      this.budgetForm.month = this.budgetToEdit.month;
      this.budgetForm.categories = this.budgetToEdit.categoriesIds;
    }
  },
  data() {
    return {
      months: [
        { number: '01', name: 'January' },
        { number: '02', name: 'February' },
        { number: '03', name: 'March' },
        { number: '04', name: 'April' },
        { number: '05', name: 'May' },
        { number: '06', name: 'June' },
        { number: '07', name: 'July' },
        { number: '08', name: 'August' },
        { number: '09', name: 'September' },
        { number: '10', name: 'October' },
        { number: '11', name: 'November' },
        { number: '12', name: 'December' }
      ],
      budgetForm: {
        year: '',
        month: '',
        categories: []
      }
    };
  },
  computed: {
    ...mapGetters({
      budgetToEdit: 'getSelectedBudgetToEdit',
      categories: 'getCategories'
    })
  },
  methods: {
    ...mapActions({
      saveBudget: 'saveBudget',
      deleteBudget: 'deleteBudget'
    }),
    toggleCategory(id) {
      const index = this.budgetForm.categories.indexOf(id);

      if (index > -1) {
        this.budgetForm.categories.splice(index, 1);
      } else {
        this.budgetForm.categories.push(id);
      }
    },
    categoryIsToggledOn(id) {
      return this.budgetForm.categories.indexOf(id) > -1;
    },
    save() {
      if (this.budgetForm.year && this.budgetForm.month) {
        this.saveBudget({
          id: this.budgetToEdit ? this.budgetToEdit.id : undefined,
          ...this.budgetForm
        });
        this.$emit('done');
      }
    },
    remove() {
      this.deleteBudget(this.budgetToEdit);
      this.$emit('done');
    }
  }
};
</script>
