import { mapGetters, mapActions } from 'vuex';

export default {
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
      return (Number(this.totalProfit) * 12 / this.budgets.length).toFixed(2);
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

    highestExpense() {
      const bounds = this.bounds
        .filter(b => b.category.expense)
      return this.getHighestExpense(bounds);
    },
    lowestExpense() {
      const bounds = this.bounds
        .filter(b => b.category.expense)
      return this.getLowestExpense(bounds);
    },
    highestRevenue() {
      const bounds = this.bounds
        .filter(b => !b.category.expense)
      return this.getHighestExpense(bounds);
    },
    lowestRevenue() {
      const bounds = this.bounds
        .filter(b => !b.category.expense)
      return this.getLowestExpense(bounds);
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
    },
    getHighestExpense(bounds) {
      const boundsByCategory = bounds
        .reduce((reduced, bound) => {
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

      if (Object.keys(boundsByCategory).length) {

        return Object.keys(boundsByCategory).reduce((selected, category) => {
          if (!selected || boundsByCategory[category] > boundsByCategory[selected]) {
            selected = category;
          }

          return selected;
        }, null);
      }
      return 'No data available';

    },
    getLowestExpense(bounds) {
      const boundsByCategory = bounds
        .reduce((reduced, bound) => {
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

      if (Object.keys(boundsByCategory).length) {

        return Object.keys(boundsByCategory).reduce((selected, category) => {
          if (!selected || boundsByCategory[category] < boundsByCategory[selected]) {
            selected = category;
          }

          return selected;
        }, null);
      }
      return 'No data available';
    }
  }
}
