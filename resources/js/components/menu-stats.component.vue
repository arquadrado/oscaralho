<template>
  <div class="stats">
    <span>
      <strong>Total expenses</strong>
    </span>
    <br>
    <br>

    <span>{{ expensesSum }}</span>
    <br>
    <br>

    <span>
      <strong>Expected expenses</strong>
    </span>
    <br>
    <br>

    <span>{{ boundsSum }}</span>
    <br>
    <br>

    <span>
      <strong>Expenses ratio</strong>
    </span>
    <br>
    <br>

    <span>{{ allTimeRatio }}</span>
    <br>
    <br>

    <span>
      <strong>Total revenues</strong>
    </span>
    <br>
    <br>

    <span>{{ revenuesSum }}</span>
    <br>
    <br>

    <span>
      <strong>Expected revenues</strong>
    </span>
    <br>
    <br>

    <span>{{ revenueBoundsSum }}</span>
    <br>
    <br>

    <span>
      <strong>Revenue ratio</strong>
    </span>
    <br>
    <br>

    <span>{{ revenueAllTimeRatio }}</span>
    <br>
    <br>

    <span>
      <strong>Total profit</strong>
    </span>
    <br>
    <br>

    <span>{{ totalProfit }}</span>
    <br>
    <br>

    <span>
      <strong>Average profit per month</strong>
    </span>
    <br>
    <br>

    <span>{{ averageProfitPerMonth }}</span>
    <br>
    <br>

    <span>
      <strong>Lowest profit month</strong>
    </span>
    <br>
    <br>

    <span>{{ lowestProfitMonth }}</span>
    <br>
    <br>

    <span>
      <strong>Highest profit month</strong>
    </span>
    <br>
    <br>

    <span>{{ highestProfitMonth }}</span>
    <br>
    <br>

    <span>
      <strong>Average profit per year</strong>
    </span>
    <br>
    <br>

    <span>{{ averageProfitPerYear }}</span>
    <br>
    <br>

    <span>
      <strong>Highest profit year</strong>
    </span>
    <br>
    <br>

    <span>{{ highestProfitYear }}</span>
    <br>
    <br>

    <span>
      <strong>Lowest profit year</strong>
    </span>
    <br>
    <br>

    <span>{{ lowestProfitYear }}</span>
    <br>
    <br>

    <span>
      <strong>Most expensive category</strong>
    </span>
    <br>
    <br>

    <span>{{ mostExpensiveCategory }}</span>
    <br>
    <br>

    <span>
      <strong>Cheapest category</strong>
    </span>
    <br>
    <br>

    <span>{{ cheapestCategory }}</span>
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
      bounds: 'getBounds',
      years: 'getAllTimeYears'
    }),
    boundsSum() {
      return Number(
        this.bounds
          .filter(b => b.category.expense)
          .reduce((sum, bound) => {
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
        this.bounds
          .filter(b => !b.category.expense)
          .reduce((sum, bound) => {
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
        this.bounds
          .reduce((profit, bound) => {
            if (bound.category.expense) {
              profit -= this.getBoundExpensesSum(bound);
            } else {
              profit += this.getBoundExpensesSum(bound);
            }
            return profit;
          }, 0)
          .toFixed(2)
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

    averageProfitPerYear() {
      return (Number(this.totalProfit) / this.years.length).toFixed(2);
    },

    highestProfitYear() {
      const yearsProfit = this.years.reduce((reduced, year) => {
        const yearProfit = this.getYearProfit(year);
        if (yearProfit) {
          reduced[year] = yearProfit;
        }
        return reduced;
      }, {});

      if (yearsProfit) {
        const highestProfitYear = Object.keys(yearsProfit).reduce(
          (reduced, year) => {
            if (!reduced || yearsProfit[year] > yearsProfit[reduced]) {
              reduced = year;
            }

            return reduced;
          }
        );

        return highestProfitYear ? highestProfitYear : 'No data available';
      }
      return 'No data available';
    },

    lowestProfitYear() {
      const yearsProfit = this.years.reduce((reduced, year) => {
        const yearProfit = this.getYearProfit(year);
        if (yearProfit) {
          reduced[year] = yearProfit;
        }
        return reduced;
      }, {});

      if (yearsProfit) {
        const highestProfitYear = Object.keys(yearsProfit).reduce(
          (reduced, year) => {
            if (!reduced || yearsProfit[year] < yearsProfit[reduced]) {
              reduced = year;
            }

            return reduced;
          }
        );

        return highestProfitYear ? highestProfitYear : 'No data available';
      }
      return 'No data available';
    },

    mostExpensiveCategory() {
      const boundsByCategory = this.bounds.reduce((reduced, bound) => {
        if (!reduced.hasOwnProperty(bound.category.name)) {
          reduced[bound.category.name] = [];
        }

        reduced[bound.category.name].push(bound);

        return reduced;
      }, {})

      Object.keys(boundsByCategory).forEach(category => {
        boundsByCategory[category] = boundsByCategory[category].reduce((sum, bound) => {
            sum += this.getBoundExpensesSum(bound);
            return sum;
          }, 0);
      });

      return Object.keys(boundsByCategory).reduce((selected, category) => {
        if (!selected || boundsByCategory[category] > boundsByCategory[selected]) {
          selected = category;
        }

        return selected;
      })
    },
    cheapestCategory() {
      const boundsByCategory = this.bounds.reduce((reduced, bound) => {
        if (!reduced.hasOwnProperty(bound.category.name)) {
          reduced[bound.category.name] = [];
        }

        reduced[bound.category.name].push(bound);

        return reduced;
      }, {})

      Object.keys(boundsByCategory).forEach(category => {
        boundsByCategory[category] = boundsByCategory[category].reduce((sum, bound) => {
            sum += this.getBoundExpensesSum(bound);
            return sum;
          }, 0);
      });

      return Object.keys(boundsByCategory).reduce((selected, category) => {
        if (!selected || boundsByCategory[category] < boundsByCategory[selected]) {
          selected = category;
        }

        return selected;
      })
    }
  },
  methods: {
    getBudgetProfit(budget) {
      return this.bounds
        .filter(b => b.budget_id === budget.id)
        .reduce((profit, bound) => {
          if (bound.category.expense) {
            profit -= this.getBoundExpensesSum(bound);
          } else {
            profit += this.getBoundExpensesSum(bound);
          }
          return profit;
        }, 0);
    },
    getYearProfit(year) {
      return this.budgets
        .filter(b => b.year === year)
        .reduce((sum, budget) => {
          sum += this.getBudgetProfit(budget);
          return sum;
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
