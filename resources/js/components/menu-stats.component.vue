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

    <span><strong>Total profit</strong></span>
    <br>
    <br>

    <span>{{ totalProfit }}</span>
    <br>
    <br>

    <span><strong>Average profit</strong></span>
    <br>
    <br>

    <span>{{ averageProfitPerMonth }}</span>
    <br>
    <br>

    <span><strong>Lowest profit month</strong></span>
    <br>
    <br>

    <span>{{ lowestProfitMonth }}</span>
    <br>
    <br>

    <span><strong>Highest profit month</strong></span>
    <br>
    <br>

    <span>{{ highestProfitMonth }}</span>
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
      budgets: 'getBudgets',
      bounds: 'getBounds'
    }),
    boundsSum() {
      return Number(
        this.bounds.filter(b => b.category.expense).reduce((sum, bound) => {
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
            sum += this.getBoundExpensesSum(bound);
            return sum;
          }, 0)
          .toFixed(2)
      );
    },
    revenueBoundsSum() {
      return Number(
        this.bounds.filter(b => !b.category.expense).reduce((sum, bound) => {
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
            sum += this.getBoundExpensesSum(bound);
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
      if (!this.revenuesSum) {
        return 'No data available';
      }
      return (this.revenuesSum / this.revenueBoundsSum).toFixed(2);
    },
    totalProfit() {
      return Number(
        this.bounds.reduce((profit, bound) => {
          if (bound.category.expense) {
            profit -= this.getBoundExpensesSum(bound)
          } else {
            profit += this.getBoundExpensesSum(bound);
          }
          return profit;
        }, 0).toFixed(2)
      );
    },

    averageProfitPerMonth() {
      return (Number(this.totalProfit) / this.budgets.length).toFixed(2);
    },

    lowestProfitMonth() {
      const lowestProfitBudget = this.budgets.reduce((reduced, budget) => {
        if (!reduced) {
          return reduced;
        }

        if (this.getBudgetProfit(budget) < this.getBudgetProfit(reduced)) {
          reduced = budget;
        }

        return reduced;
      });

      if (lowestProfitBudget) {
        return `${lowestProfitBudget.year} - ${lowestProfitBudget.month}`;
      }

      return 'No data available';
    },

    highestProfitMonth() {
      const highestProfitBudget = this.budgets.reduce((reduced, budget) => {
        if (!reduced) {
          return reduced;
        }
        if (this.getBudgetProfit(budget) > this.getBudgetProfit(reduced)) {
          reduced = budget;
        }

        return reduced;
      });

      if (highestProfitBudget) {
        return `${highestProfitBudget.year} - ${highestProfitBudget.month}`;
      }

      return 'No data available';
    },

  },
  methods: {
    getBudgetProfit(budget) {
      return this.bounds.filter(b => b.budget_id === budget.id)
        .reduce((profit, bound) => {
          if (bound.category.expense) {
            profit -= this.getBoundExpensesSum(bound);
          } else {
            profit += this.getBoundExpensesSum(bound);
          }
          return profit;
        }, 0);
    },
    getBoundExpensesSum(bound) {
      return bound.expenses.reduce((expenseSum, expense) => {
              expenseSum += Number(expense.value);
              return expenseSum;
            }, 0);
    }
  }
};
</script>
