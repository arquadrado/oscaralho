<template>
  <div class="stats">

    <span><strong>Total expenses</strong></span>
    <br>
    <br>

    <span>{{ expensesSum }}</span>
    <br>
    <br>

    <span><strong>Expected expenses</strong></span>
    <br>
    <br>

    <span>{{ boundsSum }}</span>
    <br>
    <br>

    <span><strong>Expenses ratio</strong></span>
    <br>
    <br>

    <span>{{ allTimeRatio }}</span>
    <br>
    <br>

    <span><strong>Total revenues</strong></span>
    <br>
    <br>

    <span>{{ revenuesSum }}</span>
    <br>
    <br>

    <span><strong>Expected revenues</strong></span>
    <br>
    <br>

    <span>{{ revenueBoundsSum }}</span>
    <br>
    <br>

    <span><strong>Revenue ratio</strong></span>
    <br>
    <br>

    <span>{{ revenueAllTimeRatio }}</span>
    <br>
    <br>

  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  mounted() {},
  data() {
    return {};
  },
  computed: {
    ...mapGetters({
      bounds: 'getBounds'
    }),
    boundsSum() {
      return Number(
        this.bounds.filter(b => b.category.expense).reduce((sum, bound) => {
          console.log(bound.bound_in_cents, 'bound');
          sum += Number(bound.bound_in_cents) / 100;
          return sum;
        }, 0)
      );
    },
    expensesSum() {
      return Number(
        this.bounds
          .filter(b => b.category.expense)
          .reduce((sum, bound) => {
            sum += bound.expenses.reduce((expenseSum, expense) => {
              console.log(expense.value, 'expense');
              expenseSum += Number(expense.value);
              return expenseSum;
            }, 0);
            return sum;
          }, 0)
          .toFixed(2)
      );
    },
    revenueBoundsSum() {
      return Number(
        this.bounds.filter(b => !b.category.expense).reduce((sum, bound) => {
          console.log(bound.bound_in_cents, 'bound');
          sum += Number(bound.bound_in_cents) / 100;
          return sum;
        }, 0)
      );
    },
    revenuesSum() {
      return Number(
        this.bounds
          .filter(b => !b.category.expense)
          .reduce((sum, bound) => {
            sum += bound.expenses.reduce((expenseSum, expense) => {
              console.log(expense.value, 'expense');
              expenseSum += Number(expense.value);
              return expenseSum;
            }, 0);
            return sum;
          }, 0)
          .toFixed(2)
      );
    },
    allTimeRatio() {
      if (!this.expensesSum) {
        return 'No data available';
      }
      return (this.boundsSum / this.expensesSum).toFixed(2);
    },
    revenueAllTimeRatio() {
      console.log(this.revenueBoundsSum, this.revenuesSum, 'what');
      if (!this.revenuesSum) {
        return 'No data available';
      }
      return (this.revenueBoundsSum / this.revenuesSum).toFixed(2);
    }
  },
  methods: {}
};
</script>
