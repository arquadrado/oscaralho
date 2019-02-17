<template>
    <div class="category-detail" v-show="showContent">
      <h2>{{ selectedCategory.name }}</h2>
      <div class="balance">
        <span v-if="!boundBeingEdited">{{ selectedCategoryBound }}/{{ expensesSum }}<button @click="editBound">edit</button></span>
        <span v-if="boundBeingEdited"><input v-focus type="number" v-model="newBound">/{{ expensesSum }}<button @click="saveNewBound">save</button></span>
      </div>
      <span class="unit">euros</span>

      <div class="add-form">
        <input type="number" class="add-value" v-model="expenseInput">
      </div>

      <div class="add-button" @click="addExpense">
        <i class="fa fa-plus"></i>
      </div>
      <div class="content">
        <div class="expenses">
          <div class="expense" v-for="expense in selectedCategory.expenses" :key="expense.id">
            <div class="expense-value">{{ expense.value }}</div>
            <div class="expense-notes"></div>
            <div class="expense-actions"></div>
          </div>
        </div>

      </div>
      <span class="close-button" @click="setShowDisplayPanel(false)">close</span>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";

    export default {
        mounted() {
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
            formattedPeriod() {
              const month = this.selectedMonth.length === 1 ? `0${this.selectedMonth}` : this.selectedMonth;
              return `${this.selectedYear}-${month}`;
            },
            selectedCategoryBound() {
              const bound = this.selectedCategory.bounds.find((bound) => {
                return bound.period === `${this.selectedYear}-0${this.selectedMonth + 1}`;
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
              console.log(this.newBound);
            },
        },
        directives: {
          'focus': {
            inserted: (el, binding, vnode) => {
              el.value = vnode.context.selectedCategoryBound;
              el.focus();
              console.log(el.value);
            }
          }
        }
    }
</script>
