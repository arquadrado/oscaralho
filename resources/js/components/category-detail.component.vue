<template>
    <div class="category-detail" v-if="showContent">

    <div class="compressable-content">
      <span><i :class="[boundCategory.icon]"></i></span>
      <h2>{{ boundCategory.name }}</h2>
      <div class="balance">

        <!-- <span v-if="!boundBeingEdited">{{ selectedCategoryBound }}/{{ expensesSum }}</span> -->
        <span><input v-focus :disabled="!boundBeingEdited" type="number" v-model="newBound">/{{ expensesSum }}</span>
      </div>
      <span class="unit">euros</span>
      <div class="balance-action">
        <span class="button" v-if="!boundBeingEdited" @click="editBound"><i class="fa fa-pencil"></i></span>
        <span class="button" v-if="boundBeingEdited" @click="saveNewBound"><i class="fa fa-save"></i></span>
      </div>

      <div class="content">
        <div class="expenses">
          <div class="expense" v-for="expense in selectedCategory.expenses" :key="expense.id">
            <div class="expense-value">{{ expense.value }}</div>
            <div class="expense-actions">
              <i class="fa fa-pencil"></i>
              <i class="fa fa-trash-o" @click="removeExpense(expense)"></i>
            </div>
          </div>
        </div>

      </div>
    </div>


      <!-- <div class="add-form">
        <input type="number" class="add-value" v-model="expenseInput">
      </div> -->

      <div class="category-actions">
        <span class="button" @click="addExpense"><i class="fa fa-plus"></i></span>
        <span class="button close-button" @click="close"><i class="fa fa-close"></i></span>
      </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  props: ['backgroundColor'],
  mounted() {
    this.newBound = this.selectedCategoryBound;
    setTimeout(() => {
      this.showContent = true;
    }, 100);
  },
  data() {
    return {
      showContent: false,
      expenseInput: undefined,
      newBound: this.selectedCategoryBound,
      boundBeingEdited: false
    };
  },
  computed: {
    ...mapGetters({
      categories: 'getCategories',
      selectedCategory: 'getSelectedBound',
      selectedYear: 'getSelectedYear',
      selectedMonth: 'getSelectedMonth'
    }),
    boundCategory() {
      return this.categories.find(
        c => c.id === this.selectedCategory.category.id
      );
    },
    selectedCategoryBound() {
      const bound = this.selectedCategory;

      return bound ? bound.bound_in_cents / 100 : 0;
    },
    expensesSum() {
      return this.selectedCategory.expenses.reduce((sum, expense) => {
        sum += Number(expense.value);
        return sum;
      }, 0);
    }
  },
  methods: {
    ...mapActions({
      setShowDisplayPanel: 'setShowDisplayPanel',
      saveExpense: 'addExpense',
      updateCategoryBound: 'updateCategoryBound',
      removeExpense: 'removeExpense',
      toggleModal: 'toggleModal',
      setModalType: 'setModalType',
      setModalTitle: 'setModalTitle',
      setModalMessage: 'setModalTitle',
      setModalAccept: 'setModalAccept',
      setModalReject: 'setModalReject',
      setModalColor: 'setModalColor'
    }),
    addExpense() {
      this.setModalColor(this.backgroundColor);
      this.setModalType('input-modal');
      this.setModalTitle('Add expense');
      this.setModalAccept(expenseValue => {
        if (expenseValue > 0) {
          this.saveExpense({
            value: expenseValue,
            boundId: this.selectedCategory.id
          });
        }
        this.toggleModal();
      });
      this.toggleModal();
    },
    editBound() {
      this.boundBeingEdited = true;
    },
    saveNewBound() {
      this.boundBeingEdited = false;
      this.updateCategoryBound({
        categoryId: this.selectedCategory.id,
        year: this.selectedYear,
        month: `${this.selectedMonth}`,
        value: this.newBound * 100
      });
    },
    close() {
      this.setShowDisplayPanel(false);
    }
  },
  directives: {
    focus: {
      inserted: (el, binding, vnode) => {
        el.style.width = el.value.length * 20 + 'px';
      },
      update: (el, binding, vnode) => {
        el.style.width = el.value.length * 20 + 'px';
        el.focus();
      }
    }
  }
};
</script>
