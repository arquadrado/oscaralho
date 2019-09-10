<template>
  <div id="data-visualisation">

    <div class="chart-wrapper">
      <span class="title">Expenses by category</span>
      <div class="chart">
        <polar-area-chart :chart-data="dataCollections.category.collection" :options="dataCollections.category.options"></polar-area-chart>
      </div>
      <select v-model="dataCollections.category.filters.type">
        <option :value="'expense'">Expense</option>
        <option :value="'revenue'">Revenue</option>
      </select>
    </div>

    <div class="chart-wrapper">
      <span class="title">Expense/limit evolution</span>
      <div class="chart">
        <line-chart :chart-data="dataCollections.categoryEvo.collection" :options="dataCollections.categoryEvo.options"></line-chart>
      </div>
      <select v-model="dataCollections.categoryEvo.filters.category">
        <option v-for="name in categoriesNames" :key="name" :value="name">{{ name }}</option>
        <option :value="''">All</option>
      </select>
      <select v-model="dataCollections.categoryEvo.filters.type">
        <option :value="'expense'">Expense</option>
        <option :value="'revenue'">Revenue</option>
      </select>
    </div>

    <div class="chart-wrapper">
      <span class="title">Absolute profit</span>
      <div class="chart">
        <polar-area-chart :chart-data="dataCollections.profit.collection" :options="dataCollections.profit.options"></polar-area-chart>
      </div>
      <select v-model="dataCollections.profit.filters.by">
        <option :value="'all-time'">All time</option>
        <option :value="'year'">Year</option>
        <option :value="'month'">Month</option>
      </select>
    </div>

    <div class="chart-wrapper">
      <span class="title">Average profit per year</span>
      <div class="chart">
        <polar-area-chart :chart-data="dataCollections.avgProfit.collection" :options="dataCollections.avgProfit.options"></polar-area-chart>
      </div>
    </div>

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
              display: false
            }
          }
        },
        categoryEvo: {
          collection: {},
          filters: {
            category: '',
            type: 'revenue'
          },
          options: {
            legend: {
              display: true
            }
          }
        },
        profit: {
          collection: {},
          filters: {
            by: 'year'
          },
          options: {
            legend: {
              display: false
            }
          }
        },
        avgProfit: {
          collection: {},
          filters: {
            by: 'year'
          },
          options: {
            legend: {
              display: false
            }
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

        // if (!category && this.categoriesNames.length) {
        //   this.dataCollections.categoryEvo.filters.category = this.categoriesNames[0];
        // }

        const dataset = { label: 'Expense', backgroundColor: 'rgba(255,0,0,0.5)', data: [] };
        const boundsDataset = { label: 'Limit',backgroundColor: 'rgba(0,0,255,0.5)', data: [] };

        if (!category) {
          const flatBounds = JSON.parse(JSON.stringify(this.bounds))
          .reverse()
          .filter(bound => {
            if (this.dataCollections.categoryEvo.filters.type === 'expense') {
              return bound.category.expense;
            }
            return !bound.category.expense;
          })
          .reduce((reduced, bound) => {
            let period = `${bound.year} - ${bound.month}`;
            if (!reduced[period]) {
              reduced[period] = [];
            }
            reduced[period].push(bound);
            return reduced;
          }, {});
          
          Object.keys(flatBounds).sort().forEach(period => {
            this.dataCollections.categoryEvo.collection.labels.push(period);
            dataset.data.push(
              flatBounds[period].reduce((sum, bound) => {
                sum += this.getBoundExpensesSum(bound);
                return sum;
              }, 0)
            );

            boundsDataset.data.push(
              flatBounds[period].reduce((sum, bound) => {
                sum += Number(bound.bound_in_cents / 100);
                return sum;
              }, 0)
            );
          });
        }

        if (category && boundsByCategory[category] && boundsByCategory[category].length) {

          boundsByCategory[category]
          .sort((a, b) => {
            return `${a.year} - ${a.month}` > `${b.year} - ${b.month}` ? 1 : -1;
          })
          .forEach((bound) => {
            this.dataCollections.categoryEvo.collection.labels.push(`${bound.year} - ${bound.month}`);
            dataset.data.push(this.getBoundExpensesSum(bound));
            boundsDataset.data.push(Number(bound.bound_in_cents / 100));
          });
        }

        this.dataCollections.categoryEvo.collection.datasets.push(boundsDataset);
        this.dataCollections.categoryEvo.collection.datasets.push(dataset);
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
      this.dataCollections.categoryEvo.filters.category = '';
      this.fillCategoryEvoData();
    }
  }
};
</script>
