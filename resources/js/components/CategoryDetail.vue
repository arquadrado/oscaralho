<template>
    <div class="category-detail" v-show="showContent">
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
              <i class="fa fa-trash-o"></i>
            </div>
          </div>
        </div>

      </div>

      <div class="add-form">
        <input type="number" class="add-value" v-model="expenseInput">
      </div>

      <div class="category-actions">
        <span class="button" :disabled="!expenseInput" @click="addExpense"><i class="fa fa-plus"></i></span>
        <span class="button close-button" @click="setShowDisplayPanel(false)"><i class="fa fa-close"></i></span>
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
            formattedPeriod() {
              const month = (this.selectedMonth).toString().length === 1 ? `0${this.selectedMonth + 1}` : this.selectedMonth + 1;
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
              updateCategoryBound: 'updateCategoryBound',
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
                period: this.formattedPeriod,
                value: this.newBound * 100,
              });
              console.log(this.newBound);
            },
        },
        directives: {
          'focus': {
            update: (el, binding, vnode) => {
              el.style.width = el.value.length * 20 + 'px';
              el.focus();
            }
          }
        }
    }
</script>
