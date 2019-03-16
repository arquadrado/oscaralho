<template>
  <div class="stats">

    <span><strong>Total expenses</strong></span>
    <br>
    <br>

    <span>{{ expensesSum }}</span>
    <br>
    <br>

    <span><strong>Total expections</strong></span>
    <br>
    <br>

    <span>{{ boundsSum }}</span>
    <br>
    <br>

    <span><strong>Expenses - Expectation ratio</strong></span>
    <br>
    <br>

    <span>{{ allTimeRatio }}</span>
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
        this.bounds.reduce((sum, bound) => {
          sum += bound.bound_in_cents / 100;
          return sum;
        }, 0)
      );
    },
    expensesSum() {
      return Number(
        this.bounds.reduce((sum, bound) => {
          sum += bound.expenses.reduce((expenseSum, expense) => {
            expenseSum += expense.value;
            return expenseSum;
          }, 0);
          return sum;
        }, 0)
      ).toFixed(2);
    },
    allTimeRatio() {
      return (this.boundsSum / this.expensesSum).toFixed(2);
    }
  },
  methods: {}
};
</script>
