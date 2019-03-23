<template>
    <div class="category-detail" v-if="showContent">

    <div class="compressable-content">
      <span><i :class="[boundCategory.icon]"></i></span>
      <h2>{{ boundCategory.name }}</h2>
      <div class="balance">

        <!-- <span v-if="!boundBeingEdited">{{ selectedBoundValue }}/{{ expensesSum }}</span> -->
        <span>{{ expensesSum }}/<input v-focus :disabled="!boundBeingEdited" type="number" v-model="newBound"></span>
      </div>
      <span class="unit">euros</span>
      <div class="balance-action">
        <span class="button" v-if="!boundBeingEdited" @click="editBound"><i class="fa fa-pencil"></i></span>
        <span class="button" v-if="boundBeingEdited" @click="saveNewBound"><i class="fa fa-save"></i></span>
      </div>

      <div class="content">
        <div class="expenses">
          <div class="expense" v-for="expense in selectedBound.expenses" :key="expense.id">
            <div class="expense-value">{{ expense.value }}</div>
            <div class="expense-actions">
              <i class="fa" :class="[hasNotes(expense)]"
                @click="addCommentToExpense(expense)"
                ></i>
              <i class="fa fa-trash-o" @click="deleteExpense(expense)"></i>
            </div>
          </div>
        </div>

      </div>
    </div>

      <div class="category-actions">
        <span class="button" @click="addExpense"><i class="fa fa-plus"></i></span>
        <span class="button close-button" @click="close"><i class="fa fa-arrow-right"></i></span>
      </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import ModalMixin from '../mixins/modal.mixin.js';

export default {
  mixins: [ModalMixin],
  props: ['backgroundColor'],
  mounted() {
    this.newBound = this.selectedBoundValue;
    setTimeout(() => {
      this.showContent = true;
    }, 100);
  },
  data() {
    return {
      showContent: false,
      expenseInput: undefined,
      newBound: this.selectedBoundValue,
      boundBeingEdited: false
    };
  },
  computed: {
    ...mapGetters({
      categories: 'getCategories',
      selectedBound: 'getSelectedBound',
      selectedYear: 'getSelectedYear',
      selectedMonth: 'getSelectedMonth'
    }),
    boundCategory() {
      return this.categories.find(
        c => c.id === this.selectedBound.category.id
      );
    },
    selectedBoundValue() {
      const bound = this.selectedBound;

      return bound ? bound.bound_in_cents / 100 : 0;
    },
    expensesSum() {
      return this.selectedBound.expenses
        .reduce((sum, expense) => {
          sum += Number(expense.value);
          return sum;
        }, 0)
        .toFixed(2);
    }
  },
  methods: {
    ...mapActions({
      setShowDisplayPanel: 'setShowDisplayPanel',
      saveExpense: 'addExpense',
      updateCategoryBound: 'updateCategoryBound',
      removeExpense: 'removeExpense'
    }),
    hasNotes(expense) {
      return expense.notes ? 'fa-comment' : 'fa-comment-o';
    },
    addExpense() {
      this.setModalColor(this.backgroundColor);
      this.setModalInputType('number');
      this.setModalType('input-modal');
      this.setModalTitle('Add expense');
      this.setModalAccept(expenseValue => {
        if (expenseValue > 0) {
          this.saveExpense({
            value: expenseValue,
            bound_id: this.selectedBound.id
          });
        }
        this.toggleModal();
        this.clearModal();
      });
      this.toggleModal();
    },
    deleteExpense(expense) {
      this.setModalColor(this.backgroundColor);
      this.setModalType('confirm-modal');
      this.setModalTitle('Delete expense');
      this.setModalMessage('Are you sure you want to delete this expense?');
      this.setModalAccept(() => {
        this.removeExpense(expense);
        this.toggleModal();
        this.clearModal();
      });
      this.toggleModal();
    },
    addCommentToExpense(expense) {
      this.setModalColor(this.backgroundColor);
      this.setModalType('input-modal');
      this.setModalInputType('text');
      this.setModalTitle('Add comment');
      this.setModalOnInit(function() {
        this.inputValue = expense.notes;
      });
      this.setModalAccept(comment => {
        this.saveExpense({
          ...expense,
          notes: comment
        });
        this.toggleModal();
        this.clearModal();
      });
      this.toggleModal();
    },
    editBound() {
      this.boundBeingEdited = true;
    },
    saveNewBound() {
      this.boundBeingEdited = false;
      this.updateCategoryBound({
        boundId: this.selectedBound.id,
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
