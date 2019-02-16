<template>
    <div class="category-detail" v-show="showContent">
      <h2>{{ selectedCategory.name }}</h2>
      <div class="balance">
        <span>100/87.78</span>
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
          }
        },
        computed: {
            ...mapGetters({
              selectedCategory: 'getSelectedCategory'
            }),
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
            }
        }
    }
</script>
