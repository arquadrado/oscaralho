<template>
  <div id="data-visualisation">
    <polar-area-chart :chart-data="dataCollections.category.collection"></polar-area-chart>
    <select v-model="dataCollections.category.filters.type">
      <option :value="'expense'">Expense</option>
      <option :value="'revenue'">Revenue</option>
    </select>

    <br>
    <hr>
    <polar-area-chart :chart-data="dataCollections.profit.collection"></polar-area-chart>
    <select v-model="dataCollections.profit.filters.by">
      <option :value="'all-time'">All time</option>
      <option :value="'year'">Year</option>
      <option :value="'month'">Month</option>
    </select>

    <polar-area-chart :chart-data="dataCollections.avgProfit.collection"></polar-area-chart>
    <select v-model="dataCollections.avgProfit.filters.by">
      <option :value="'all-time'">All time</option>
      <option :value="'year'">Year</option>
    </select>

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
      dataCollections: {
        category: {
          collection: {},
          filters: {
            type: 'expense'
          }
        },
        categoryEvo: {
          collection: {},
          filters: {
            category: undefined
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
            // let label = `${budget.year} - ${budget.month}`;
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

        if (this.dataCollections.avgProfit.filters.by === 'all-time') {
          organizedBudgets = {
            'all-time': this.budgets
          }
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
                }, 0) / organizedBudgets[label].length
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
    }
  }
};
</script>
