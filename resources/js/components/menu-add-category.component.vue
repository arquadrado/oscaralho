<template>
  <div class="add-category">
    
    <div class="form-field">
      <label for="category-name">Name</label>
      <input type="text" name="category-name" v-model="categoryForm.name">
    </div>

    <div class="form-field">
      <label for="category-default-bound">Default bound</label>
      <input type="number" name="category-default-bound" v-model="categoryForm.default_bound">
    </div>

    <div class="form-field">
      <label for="category-icon">Icon</label>
      <input type="text" name="category-icon" v-model="categoryForm.icon">
    </div>

    <div class="form-field">
      <label for="category-type">Type</label>
      <select name="category-type" v-model="categoryForm.type">
        <option v-for="t in types" :key="t" :value="t">{{ t }}</option>
      </select>
    </div>
    
    <div class="actions">

      <span class="button" @click="save">
        <i class="fa fa-save"></i>
      </span>

      <span class="button" v-if="categoryToEdit && canRemoveCategory" @click="remove">
        <i class="fa fa-remove"></i>
      </span>
    </div>


  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  mounted() {
    console.log(this.categoryToEdit);
    if (this.categoryToEdit) {
      this.categoryForm.name = this.categoryToEdit.name;
      this.categoryForm.default_bound =
        this.categoryToEdit.default_bound_in_cents / 100;
      this.categoryForm.icon = this.categoryToEdit.icon;
      this.categoryForm.type = this.categoryToEdit.expense
        ? 'expense'
        : 'revenue';
    }
  },
  data() {
    return {
      types: ['expense', 'revenue'],
      categoryForm: {
        name: '',
        default_bound: 0,
        icon: 'fa-square',
        type: 'expense'
      }
    };
  },
  computed: {
    ...mapGetters({
      bounds: 'getBounds',
      categoryToEdit: 'getSelectedCategoryToEdit'
    }),
    canRemoveCategory() {
      return !this.bounds.some(b => b.category_id === this.categoryToEdit.id);
    }
  },
  methods: {
    ...mapActions({
      saveCategory: 'saveCategory',
      deleteCategory: 'deleteCategory'
    }),
    save() {
      if (this.categoryForm.name) {
        this.saveCategory({
          id: this.categoryToEdit ? this.categoryToEdit.id : undefined,
          ...this.categoryForm
        });
        this.$emit('done');
      }
    },
    remove() {
      this.deleteCategory(this.categoryToEdit);
      this.$emit('done');
    }
  }
};
</script>
