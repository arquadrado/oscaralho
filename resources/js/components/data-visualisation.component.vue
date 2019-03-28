<template>
  <div id="data-visualisation">
    <h2>Expenses by category</h2>
    <polar-area-chart :chart-data="dataCollections.category.collection" :options="dataCollections.category.options"></polar-area-chart>
    <select v-model="dataCollections.category.filters.type">
      <option :value="'expense'">Expense</option>
      <option :value="'revenue'">Revenue</option>
    </select>

    <br>
    <hr>

    <h2>Category expense evolution</h2>
    <line-chart :chart-data="dataCollections.categoryEvo.collection" :options="dataCollections.categoryEvo.options"></line-chart>
    <select v-model="dataCollections.categoryEvo.filters.category">
      <option v-for="name in categoriesNames" :key="name" :value="name">{{ name }}</option>
    </select>
    <select v-model="dataCollections.categoryEvo.filters.type">
      <option :value="'expense'">Expense</option>
      <option :value="'revenue'">Revenue</option>
    </select>

    <br>
    <hr>

    <h2>Absolute profit</h2>
    <polar-area-chart :chart-data="dataCollections.profit.collection"></polar-area-chart>
    <select v-model="dataCollections.profit.filters.by">
      <option :value="'all-time'">All time</option>
      <option :value="'year'">Year</option>
      <option :value="'month'">Month</option>
    </select>

    <br>
    <hr>
    <h2>Average profit per year</h2>
    <polar-area-chart :chart-data="dataCollections.avgProfit.collection"></polar-area-chart>

  </div>
</template>

<script>
import PolarAreaChart from '../charts/polar-area.chart.js';
import LineChart from '../charts/line.chart.js';
import StatsMixin from '../mixins/stats.mixins.js';
import { mapGetters, mapActions } from 'vuex';

export default {
  mixins: [StatsMixin],
  components: {
    PolarAreaChart,
    LineChart
  },
  data() {
    return {
      categoriesNames: [],
      dataCollections: {
        category: {
          collection: {},
          filters: {
            type: 'expense'
          },
          options: {
            legend: {
              display: true
            }
          }
        },
        categoryEvo: {
          collection: {},
          filters: {
            category: undefined,
            type: 'expense'
          },
          options: {
            legend: {
              display: false
            }
          }
        },
        profit: {
          collection: {},
          filters: {
            by: 'year'
          }
        },
        avgProfit: {
          collection: {},
          filters: {
            by: 'year'
          }
        }
      },
    };
  },
  mounted() {
    this.fillCategoryData();
    this.fillCategoryEvoData();
    this.fillProfitData();
    this.fillAvgProfitData();
  },
  computed: {
    ...mapGetters({})
  },
  methods: {
    fillCategoryEvoData() {
      this.dataCollections.categoryEvo.collection = {
        labels: [],
        datasets: []
      };
      const boundsByCategory = this.bounds
        .filter(bound => {
          if (this.dataCollections.categoryEvo.filters.type === 'expense') {
            return bound.category.expense;
          }
          return !bound.category.expense;
        })
        .reduce((reduced, bound) => {
          if (!reduced[bound.category.name]) {
            reduced[bound.category.name] = [];
          }
          reduced[bound.category.name].push(bound);
          return reduced;
        }, {});

        this.categoriesNames = Object.keys(boundsByCategory);

        const category = this.dataCollections.categoryEvo.filters.category;
        if (category) {
          const dataset = { backgroundColor: [], data: [] };

          boundsByCategory[category]
          .reverse() 
          .forEach((bound) => {
            this.dataCollections.categoryEvo.collection.labels.push(`${bound.year} - ${bound.month}`);
            dataset.backgroundColor.push(this.getRandomColor());
            dataset.data.push(this.getBoundExpensesSum(bound));
          });
          this.dataCollections.categoryEvo.collection.datasets.push(dataset);
        }
    },
    fillCategoryData() {
      this.dataCollections.category.collection = {
        labels: [],
        datasets: []
      };
      const boundsByCategory = this.bounds
        .filter(bound => {
          if (this.dataCollections.category.filters.type === 'expense') {
            return bound.category.expense;
          }
          return !bound.category.expense;
        })
        .reduce((reduced, bound) => {
          if (!reduced[bound.category.name]) {
            reduced[bound.category.name] = [];
          }
          reduced[bound.category.name].push(bound);
          return reduced;
        }, {});

      const dataset = { backgroundColor: [], data: [] };

      Object.keys(boundsByCategory).forEach(category => {
        this.dataCollections.category.collection.labels.push(category);
        dataset.backgroundColor.push(this.getRandomColor());
        dataset.data.push(
          boundsByCategory[category].reduce((sum, bound) => {
            sum += bound.expenses_sum;
            return sum;
          }, 0)
        );
      });

      this.dataCollections.category.collection.datasets.push(dataset);
    },
    fillProfitData() {
      this.dataCollections.profit.collection = {
        labels: [],
        datasets: []
      };

      let organizedBudgets;
      if (this.dataCollections.profit.filters.by === 'year') {
          organizedBudgets = this.budgets.reduce((reduced, budget) => {
            let label = budget.year;
            if (!reduced[label]) {
              reduced[label] = [];
            }

            reduced[label].push(budget);
            return reduced;
          }, {})
        }

        if (this.dataCollections.profit.filters.by === 'month') {
          organizedBudgets = this.budgets.reduce((reduced, budget) => {
            let label = `${budget.year} - ${budget.month}`;
            if (!reduced[label]) {
              reduced[label] = [];
            }

            reduced[label].push(budget);
            return reduced;
          }, {})
        }

        if (this.dataCollections.profit.filters.by === 'all-time') {
          organizedBudgets = {
            'all-time': this.budgets
          }
        }

        if (organizedBudgets) {
          const labels = Object.keys(organizedBudgets);
          if (labels.length) {
            const dataset = { backgroundColor: [], data: [] };
            labels.forEach((label) => {
              this.dataCollections.profit.collection.labels.push(label);
              dataset.backgroundColor.push(this.getRandomColor());
              dataset.data.push(
                organizedBudgets[label].reduce((sum, budget) => {
                  sum += this.getBudgetProfit(budget);
                  return sum;
                }, 0)
              );
            });
            this.dataCollections.profit.collection.datasets.push(dataset);
          }
        }

    },

    fillAvgProfitData() {
      this.dataCollections.avgProfit.collection = {
        labels: [],
        datasets: []
      };

      let organizedBudgets;
      if (this.dataCollections.avgProfit.filters.by === 'year') {
          organizedBudgets = this.budgets.reduce((reduced, budget) => {
            let label = budget.year;
            if (!reduced[label]) {
              reduced[label] = [];
            }

            reduced[label].push(budget);
            return reduced;
          }, {})
        }

        if (organizedBudgets) {
          const labels = Object.keys(organizedBudgets);
          if (labels.length) {
            const dataset = { backgroundColor: [], data: [] };
            labels.forEach((label) => {
              this.dataCollections.avgProfit.collection.labels.push(label);
              dataset.backgroundColor.push(this.getRandomColor());
              dataset.data.push(
                organizedBudgets[label].reduce((sum, budget) => {
                  sum += this.getBudgetProfit(budget);
                  return sum;
                }, 0) / organizedBudgets[label].length * 12
              );
            });
            this.dataCollections.avgProfit.collection.datasets.push(dataset);
          }
        }
    },
    getRandomColor() {
      const r = Math.floor(Math.random()*256);
      const g = Math.floor(Math.random()*256);
      const b = Math.floor(Math.random()*256);
      const rgb = 'rgb(' + r + ',' + g + ',' + b + ',0.5)';
      return rgb;
    }
  },
  watch: {
    'dataCollections.category.filters.type': function() {
      this.fillCategoryData();
    },
    'dataCollections.profit.filters.by': function() {
      this.fillProfitData();
    },
    'dataCollections.avgProfit.filters.by': function() {
      this.fillAvgProfitData();
    },
    'dataCollections.categoryEvo.filters.category': function() {
      this.fillCategoryEvoData();
    },
    'dataCollections.categoryEvo.filters.type': function() {
      console.log('waht');
      this.fillCategoryEvoData();
    }
  }
};
</script>
