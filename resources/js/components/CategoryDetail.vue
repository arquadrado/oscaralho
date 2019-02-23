<template>
    <div class="category-detail" v-if="showContent">
      <span><i :class="[selectedCategory.icon]"></i></span>
      <h2>{{ selectedCategory.name }}</h2>
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

      <div class="add-form">
        <input v-add-focus type="number" class="add-value" v-model="expenseInput">
      </div>

      <div class="category-actions">
        <span class="button" :class="{'disabled': !expenseInput}" @click="addExpense"><i class="fa fa-plus"></i></span>
        <span class="button close-button" @click="close"><i class="fa fa-close"></i></span>
      </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";

    export default {
        mounted() {
          this.newBound = this.selectedCategoryBound;
          setTimeout(() => {
            this.showContent = true;
          }, 100)
        },
        data() {
          return {
            showContent: false,
            expenseInput: undefined,
            newBound: this.selectedCategoryBound,
            boundBeingEdited: false,
          }
        },
        computed: {
            ...mapGetters({
              selectedCategory: 'getSelectedCategory',
              selectedYear: 'getSelectedYear',
              selectedMonth: 'getSelectedMonth',
            }),
            selectedCategoryBound() {
              const bound = this.selectedCategory.bounds.find((bound) => {
                return bound.year === this.selectedYear && bound.month === `0${this.selectedMonth + 1}`;
              });

              return bound ? bound.bound_in_cents / 100 : 0;
            },
            expensesSum() {
              return this.selectedCategory.expenses.reduce((sum, expense) => {
                sum += Number(expense.value);
                return sum;
              }, 0)
            },
        },
        methods: {
            ...mapActions({
              setShowDisplayPanel: 'setShowDisplayPanel',
              saveExpense: 'addExpense',
              updateCategoryBound: 'updateCategoryBound',
              removeExpense: 'removeExpense',
            }),
            addExpense() {
              if (this.expenseInput > 0) {
                this.saveExpense({
                  value: this.expenseInput,
                  categoryId: this.selectedCategory.id
                });
                this.expenseInput = undefined;
              }
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
                value: this.newBound * 100,
              });
            },
            close() {
              this.setShowDisplayPanel(false);
            }
        },
        directives: {
          'focus': {
            inserted: (el, binding, vnode) => {
              el.style.width = el.value.length * 20 + 'px';
            },
            update: (el, binding, vnode) => {
              el.style.width = el.value.length * 20 + 'px';
              el.focus();
            },
          },
          'add-focus': {
            inserted: (el, binding, vnode) => {
              el.focus();
            }
          }
        }
    }
</script>
